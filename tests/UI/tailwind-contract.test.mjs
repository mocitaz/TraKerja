import assert from 'node:assert/strict';
import fs from 'node:fs/promises';
import postcss from 'postcss';
import tailwind from 'tailwindcss';
import config from '../../tailwind.config.js';

const states = [
    ['text-ui-muted', 'bg-[#fafafa]'],
    ['text-ui-strong', 'bg-primary-50'],
    ['hover:text-ui-strong', 'hover:bg-primary-50/50'],
    ['disabled:text-ui-disabled', 'disabled:bg-zinc-100'],
    ['text-white', 'bg-zinc-800'],
];
const fixture = states.flat().concat(['text-xs', 'text-sm', 'shadow-3xs', 'shadow-2xs', 'shadow-xs', 'w-5.5', 'h-6.5', 'active:scale-97', 'backdrop-blur-xs', 'text-zinc-850', 'text-zinc-555', 'hover:text-zinc-850', 'focus:outline-hidden']);
const source = await fs.readFile(new URL('../../resources/css/app.css', import.meta.url), 'utf8');
const compiled = await postcss([tailwind({ ...config, content: [...config.content, { raw: `<div class="${fixture.join(' ')}"></div>` }] })]).process(source, { from: undefined });
const stylesheet = process.argv.includes('--built')
    ? postcss.parse(await fs.readFile(new URL('../../public/build/' + JSON.parse(await fs.readFile(new URL('../../public/build/manifest.json', import.meta.url), 'utf8'))['resources/css/app.css'].file, import.meta.url), 'utf8'))
    : compiled.root;
const declarations = new Map();
stylesheet.walkRules(rule => {
    for (const selector of rule.selectors) {
        const parsed = selector.match(/^\.((?:\\.|[^\s:.])+)(?::hover|:active|:focus|:disabled)?$/);
        if (!parsed) continue;
        const className = parsed[1].replace(/\\(.)/g, '$1');
        const values = Object.fromEntries(rule.nodes.filter(node => node.type === 'decl').map(node => [node.prop, node.value]));
        declarations.set(className, { ...declarations.get(className), ...values });
    }
});
const declaration = (name, property) => {
    const value = declarations.get(name)?.[property];
    assert.ok(value, `${name} must compile to ${property}`);
    return value;
};
const rgb = value => {
    if (value.startsWith('#')) {
        let hex = value.slice(1);
        if (hex.length === 3 || hex.length === 4) hex = [...hex].map(digit => digit + digit).join('');
        const channels = hex.match(/../g).map(channel => parseInt(channel, 16));
        return [...channels.slice(0, 3), channels.length === 4 ? channels[3] / 255 : 1];
    }
    const channels = value.match(/^rgb\((\d+) (\d+) (\d+)\s*\/\s*(.+)\)$/);
    assert.ok(channels, `Expected compiled RGB color, got ${value}`);
    return [...channels.slice(1, 4).map(Number), channels[4].includes('var(') ? 1 : Number(channels[4])];
};
const luminance = channels => channels.reduce((sum, channel, index) => {
    const value = channel / 255;
    return sum + (value <= 0.04045 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4) * [0.2126, 0.7152, 0.0722][index];
}, 0);
for (const [foregroundClass, backgroundClass] of states) {
    const foreground = rgb(declaration(foregroundClass, 'color'));
    const background = rgb(declaration(backgroundClass, 'background-color'));
    const composite = background.slice(0, 3).map(channel => background[3] * channel + (1 - background[3]) * 250);
    const a = luminance(foreground.slice(0, 3)), b = luminance(composite);
    const ratio = (Math.max(a, b) + 0.05) / (Math.min(a, b) + 0.05);
    assert.ok(ratio >= 4.5, `${foregroundClass} on ${backgroundClass}: ${ratio.toFixed(2)} must be >= 4.5`);
    console.log(`${foregroundClass} on ${backgroundClass}: ${ratio.toFixed(2)}:1`);
}
assert.equal(parseFloat(declaration('text-xs', 'font-size')), 0.75);
assert.equal(parseFloat(declaration('text-sm', 'font-size')), 0.875);
assert.equal(declaration('w-5.5', 'width'), '1.375rem');
assert.equal(declaration('h-6.5', 'height'), '1.625rem');
assert.equal(declaration('active:scale-97', '--tw-scale-x'), '.97');
for (const name of ['shadow-3xs', 'shadow-2xs', 'shadow-xs']) declaration(name, '--tw-shadow');
declaration('backdrop-blur-xs', '--tw-backdrop-blur');
assert.equal(declaration('focus:outline-hidden', 'outline'), '2px solid transparent');
for (const name of ['text-zinc-850', 'text-zinc-555', 'hover:text-zinc-850']) rgb(declaration(name, 'color'));
console.log('Compiled Tailwind utility and contrast contracts passed.');
