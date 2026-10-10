import test from 'node:test';
import assert from 'node:assert/strict';
import { installToasts } from '../../resources/js/toasts.js';

class Node {
    children = []; attributes = {}; style = {}; handlers = {}; className = ''; textContent = '';
    classList = { add: (...names) => { this.className += ' ' + names.join(' '); } };
    get firstChild() { return this.children[0]; }
    append(...nodes) { nodes.forEach(node => this.appendChild(node)); }
    appendChild(node) { node.parent = this; this.children.push(node); }
    setAttribute(key, value) { this.attributes[key] = value; }
    addEventListener(name, fn) { (this.handlers[name] ||= []).push(fn); }
    emit(name, event = {}) { this.handlers[name]?.forEach(fn => fn(event)); }
    contains(node) { return this === node || this.children.some(child => child.contains(node)); }
    remove() { this.parent.children = this.parent.children.filter(node => node !== this); }
}
function harness() {
    const container = new Node();
    let time = 0, nextId = 0;
    const timers = new Map();
    const doc = new Node();
    doc.hidden = false;
    doc.createElement = () => new Node();
    doc.getElementById = () => container;
    const win = new Node();
    win.performance = { now: () => time };
    win.setTimeout = (fn, ms) => { const id = ++nextId; timers.set(id, { at: time + ms, fn }); return id; };
    win.clearTimeout = id => timers.delete(id);
    const advance = ms => {
        const end = time + ms;
        while (true) {
            const pending = [...timers.entries()].filter(([, timer]) => timer.at <= end).sort((a,b) => a[1].at - b[1].at)[0];
            if (!pending) break;
            const [id, timer] = pending;
            time = timer.at; timers.delete(id); timer.fn();
        }
        time = end;
    };
    installToasts(win, doc);
    return { win, doc, container, advance, timers };
}

test('messages stay plain text, preserve long content, and use an accessible close button', () => {
    const { win, container } = harness();
    win.showToast('error', 'Save failed', '<img src=x onerror=alert(1)>', 0, 'ph-warning-circle "><script>');
    const toast = container.firstChild;
    assert.equal(toast.attributes.role, 'alert');
    assert.equal(toast.children[1].children[1].textContent, '<img src=x onerror=alert(1)>');
    assert.equal(toast.children[2].attributes['aria-label'], 'Dismiss notification');
    assert.equal(toast.children[0].firstChild.className, 'ph-bold ph-warning-circle');
});

test('hover and keyboard focus pause dismissal independently and resume remaining time', () => {
    const { win, container, advance } = harness();
    win.showToast('success', '', 'Saved', 3500);
    const toast = container.firstChild;
    advance(1000);
    toast.emit('mouseenter'); toast.emit('focusin');
    advance(5000); toast.emit('mouseleave'); advance(5000);
    assert.equal(container.children.length, 1);
    toast.emit('focusout', { relatedTarget: null });
    advance(2499); assert.ok(!toast.className.includes('is-leaving'));
    advance(1); assert.ok(toast.className.includes('is-leaving'));
    advance(180); assert.equal(container.children.length, 0);
});

test('Livewire payloads and aliases work once after repeated installation, including duration zero', () => {
    const { win, doc, container, advance } = harness();
    installToasts(win, doc);
    win.emit('showNotification', { detail: [{ type: 'info', message: 'Persistent', duration: 0 }] });
    advance(10000); assert.equal(container.children.length, 1);
    assert.equal(container.firstChild.children.length, 3);
    win.emit('showToast', { detail: 'Draft ready' });
    win.showToastNotification({ body: 'Another update' });
    assert.equal(container.children.length, 3);
    doc.emit('livewire:navigating'); assert.equal(container.children.length, 0);
});

test('hidden tabs pause time and dismissal is safe when close and timeout overlap', () => {
    const { win, doc, container, advance } = harness();
    win.showToast('warning', 'Check this', 'Details', 2000);
    advance(500); doc.hidden = true; doc.emit('visibilitychange'); advance(10000);
    assert.equal(container.children.length, 1);
    doc.hidden = false; doc.emit('visibilitychange'); advance(1499);
    container.firstChild.children[2].emit('click'); advance(181);
    assert.equal(container.children.length, 0);
});
