// TraKerja · social media job pamphlet, feed (1080 × 1350) or story (1080 × 1920), drawn on a canvas so
// preview and download are identical. The story keeps text out of the top/bottom ~14% that the app UI covers.
// Same-origin images only (company logos come through /admin/promosi/logo/{id}), so the canvas stays exportable.

const W = 1080;
const C = { ink: '#1d1a22', ink2: '#3f3a46', muted: '#7a7480', faint: '#a49eaa', plum: '#4b2e6b', plumDeep: '#2d204e', plumSoft: '#f1ebf7', green: '#16704a', line: '#ece7f0', panel: '#f8f6fa' };
const FAMILY = 'Plus Jakarta Sans';
const images = new Map();
let fontReady = null;

function loadImage(src) {
    if (!src) return Promise.resolve(null);
    if (!images.has(src)) {
        images.set(src, new Promise(resolve => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = () => resolve(null);
            img.src = src;
        }));
    }
    return images.get(src);
}

/** Plus Jakarta Sans is already loaded by the admin layout (fonts.bunny.net); wait for the weights we draw with. */
function loadFont() {
    if (!fontReady) {
        fontReady = Promise.all(['500', '600', '700', '800'].map(w => document.fonts.load(`${w} 48px "${FAMILY}"`)))
            .then(() => (document.fonts.check(`700 48px "${FAMILY}"`) ? `"${FAMILY}"` : 'system-ui'))
            .catch(() => 'system-ui');
    }
    return fontReady;
}

function roundRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
}

/** Wrap text into at most maxLines lines; the last line gets an ellipsis when it overflows. */
function wrap(ctx, text, maxWidth, maxLines) {
    const words = String(text || '').split(/\s+/).filter(Boolean);
    const lines = [];
    let line = '';
    for (const word of words) {
        const test = line ? `${line} ${word}` : word;
        if (ctx.measureText(test).width <= maxWidth || !line) { line = test; continue; }
        lines.push(line);
        line = word;
        if (lines.length === maxLines) break;
    }
    if (lines.length < maxLines && line) lines.push(line);
    const used = lines.join(' ').split(/\s+/).filter(Boolean).length;
    if (used < words.length && lines.length) {
        let last = lines[lines.length - 1];
        while (ctx.measureText(`${last}…`).width > maxWidth && last.includes(' ')) last = last.slice(0, last.lastIndexOf(' '));
        lines[lines.length - 1] = `${last}…`;
    }
    return lines;
}

function pill(ctx, x, y, text, bg, fg, font, padX = 20, h = 48) {
    ctx.font = font;
    const w = ctx.measureText(text).width + padX * 2;
    ctx.fillStyle = bg;
    roundRect(ctx, x, y, w, h, h / 2);
    ctx.fill();
    ctx.fillStyle = fg;
    ctx.textBaseline = 'middle';
    ctx.fillText(text, x + padX, y + h / 2 + 1);
    ctx.textBaseline = 'alphabetic';
    return w;
}

/** The brand icon recoloured to solid white (for use on the dark hero). */
function whiteIcon(img, size) {
    const c = document.createElement('canvas');
    c.width = c.height = size * 2;
    const x = c.getContext('2d');
    x.drawImage(img, 0, 0, c.width, c.height);
    x.globalCompositeOperation = 'source-in';
    x.fillStyle = '#ffffff';
    x.fillRect(0, 0, c.width, c.height);
    return c;
}

async function render(canvas, d) {
    const family = await loadFont();
    const [icon, logo] = await Promise.all([loadImage(d.brandIcon), loadImage(d.logo)]);
    const story = d.format === 'story';
    const H = story ? 1920 : 1350;
    canvas.width = W;
    canvas.height = H;
    const ctx = canvas.getContext('2d');
    const f = (size, weight = 500) => `${weight} ${size}px ${family}, system-ui, sans-serif`;
    const track = (px) => { if ('letterSpacing' in ctx) ctx.letterSpacing = `${px}px`; };
    const M = 48;                 // outer margin
    const top = story ? 230 : M;  // hero top (story: below the profile/progress bar)
    const heroH = story ? 900 : 820; // hero panel height
    const px = M + 64;            // content left edge inside the hero
    const inner = W - px * 2;

    // Page.
    ctx.fillStyle = '#f6f4f9';
    ctx.fillRect(0, 0, W, H);

    // Hero panel: deep plum gradient, soft glow, oversized brand chevrons as texture.
    ctx.save();
    roundRect(ctx, M, top, W - M * 2, heroH, 40);
    ctx.clip();
    const g = ctx.createLinearGradient(M, top, W - M, top + heroH);
    g.addColorStop(0, '#1f1638');
    g.addColorStop(0.55, '#33225a');
    g.addColorStop(1, '#5a2c7c');
    ctx.fillStyle = g;
    ctx.fillRect(M, top, W - M * 2, heroH);
    const glow = ctx.createRadialGradient(W - 180, top + 160, 10, W - 180, top + 160, 520);
    glow.addColorStop(0, 'rgba(233, 120, 214, 0.35)');
    glow.addColorStop(1, 'rgba(233, 120, 214, 0)');
    ctx.fillStyle = glow;
    ctx.fillRect(M, top, W - M * 2, heroH);
    if (icon) {
        // Large brand chevrons as texture, tinted white at the same 10% opacity.
        const tint = document.createElement('canvas');
        tint.width = tint.height = 560;
        const t = tint.getContext('2d');
        t.drawImage(icon, 0, 0, 560, 560);
        t.globalCompositeOperation = 'source-in';
        t.fillStyle = '#ffffff';
        t.fillRect(0, 0, 560, 560);
        ctx.globalAlpha = 0.10;
        ctx.drawImage(tint, W - 520, top + heroH - 470);
        ctx.globalAlpha = 1;
    }
    ctx.restore();

    // Brand row: "LOWONGAN KERJA" on the left, white TraKerja mark + wordmark on the right.
    let y = top + (story ? 80 : 64);
    track(2);
    ctx.font = f(18, 700);
    ctx.fillStyle = 'rgba(255, 255, 255, 0.72)';
    ctx.fillText('LOWONGAN KERJA', px, y + 31);
    ctx.fillStyle = '#ffffff';
    ctx.font = f(34, 800);
    track(-0.6);
    const word = 'TraKerja';
    const wordX = W - px - ctx.measureText(word).width;
    ctx.fillText(word, wordX, y + 35);
    track(0);
    if (icon) ctx.drawImage(whiteIcon(icon, 46), wordX - 58, y, 46, 46);

    // Company row.
    y += 46 + (story ? 96 : 72);
    const ls = 84;
    ctx.save();
    roundRect(ctx, px, y, ls, ls, 22);
    ctx.fillStyle = '#ffffff';
    ctx.fill();
    ctx.clip();
    if (logo) {
        const sc = Math.min((ls - 16) / logo.width, (ls - 16) / logo.height);
        ctx.drawImage(logo, px + (ls - logo.width * sc) / 2, y + (ls - logo.height * sc) / 2, logo.width * sc, logo.height * sc);
    } else {
        ctx.fillStyle = C.plum;
        ctx.font = f(36, 800);
        ctx.textAlign = 'center';
        ctx.fillText((d.company || '?').replace(/^(pt|cv)\.?\s+/i, '').slice(0, 1).toUpperCase(), px + ls / 2, y + ls / 2 + 13);
        ctx.textAlign = 'left';
    }
    ctx.restore();
    const tx = px + ls + 24;
    ctx.fillStyle = '#ffffff';
    ctx.font = f(30, 700);
    ctx.fillText(wrap(ctx, d.company, inner - ls - 24, 1)[0] || '', tx, y + 38);
    ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
    ctx.font = f(24, 500);
    if (d.location) ctx.fillText(wrap(ctx, d.location, inner - ls - 24, 1)[0], tx, y + 74);

    // Headline: calm, bounded size (64 → 50 px), up to three lines.
    y += ls + (story ? 84 : 64);
    let size = 64, head = [];
    for (; size >= 50; size -= 2) {
        ctx.font = f(size, 800);
        track(-size * 0.02);
        head = wrap(ctx, d.headline, inner, 3);
        if (!head[head.length - 1].endsWith('…')) break;
    }
    // Balance the lines: the narrowest width that keeps the same line count (no lonely last word).
    if (head.length > 1) {
        for (let w = inner - 10; w > inner * 0.5; w -= 10) {
            const test = wrap(ctx, d.headline, w, 3);
            if (test.length !== head.length || test[test.length - 1].endsWith('…')) break;
            head = test;
        }
    }
    const lh = Math.round(size * 1.16);
    ctx.fillStyle = '#ffffff';
    ctx.font = f(size, 800);
    track(-size * 0.02);
    head.forEach((line, k) => ctx.fillText(line, px, y + size * 0.9 + k * lh));
    track(0);
    y += head.length * lh + 28;

    // Level chip (translucent on the hero).
    if (d.level) {
        ctx.font = f(22, 700);
        const tw = ctx.measureText(d.level).width;
        ctx.fillStyle = 'rgba(255, 255, 255, 0.14)';
        roundRect(ctx, px, y, tw + 40, 46, 23);
        ctx.fill();
        ctx.fillStyle = '#ffffff';
        ctx.textBaseline = 'middle';
        ctx.fillText(d.level, px + 20, y + 24);
        ctx.textBaseline = 'alphabetic';
    }

    // Details card, overlapping the hero's lower edge.
    const cardX = M + 32, cardW = W - (M + 32) * 2, rowH = 120;
    const cardY = top + heroH - 64;
    const cardH = rowH * 2 + 16;
    ctx.save();
    ctx.shadowColor = 'rgba(31, 22, 56, 0.16)';
    ctx.shadowBlur = 48;
    ctx.shadowOffsetY = 18;
    ctx.fillStyle = '#ffffff';
    roundRect(ctx, cardX, cardY, cardW, cardH, 28);
    ctx.fill();
    ctx.restore();
    ctx.strokeStyle = C.line;
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(cardX + cardW / 2, cardY + 28);
    ctx.lineTo(cardX + cardW / 2, cardY + cardH - 28);
    ctx.moveTo(cardX + 32, cardY + 8 + rowH);
    ctx.lineTo(cardX + cardW - 32, cardY + 8 + rowH);
    ctx.stroke();
    const details = [
        ['LOKASI', d.location || '—'],
        ['SISTEM KERJA', d.workMode || '—'],
        ['TIPE PEKERJAAN', d.employmentType || '—'],
        ['BATAS LAMAR', d.deadline || 'Selagi masih buka'],
    ];
    details.forEach(([label, value], k) => {
        const dx = cardX + 44 + (k % 2) * (cardW / 2);
        const dy = cardY + 8 + Math.floor(k / 2) * rowH;
        ctx.fillStyle = C.faint;
        ctx.font = f(18, 700);
        track(1.6);
        ctx.fillText(label, dx, dy + 44);
        track(0);
        ctx.fillStyle = C.ink;
        ctx.font = f(30, 700);
        ctx.fillText(wrap(ctx, value, cardW / 2 - 76, 1)[0], dx, dy + 86);
    });

    // CTA.
    const ctaY = cardY + cardH + 48;
    ctx.fillStyle = C.plumDeep;
    roundRect(ctx, cardX, ctaY, cardW, 96, 48);
    ctx.fill();
    ctx.fillStyle = '#ffffff';
    ctx.font = f(30, 700);
    ctx.textBaseline = 'middle';
    ctx.fillText('Lamar sekarang', cardX + 44, ctaY + 49);
    ctx.font = f(24, 600);
    ctx.fillStyle = '#d8c8ea';
    const hint = story ? 'Ketuk stiker link  ↗' : 'Link ada di caption  →';
    ctx.fillText(hint, cardX + cardW - 44 - ctx.measureText(hint).width, ctaY + 49);
    ctx.textBaseline = 'alphabetic';

    // Footer.
    ctx.textAlign = 'center';
    ctx.fillStyle = C.ink2;
    ctx.font = f(26, 700);
    const footY = story ? ctaY + 96 + 96 : H - 74;
    ctx.fillText('trakerja.com/loker', W / 2, footY);
    ctx.fillStyle = C.muted;
    ctx.font = f(21, 500);
    ctx.fillText('Loker dicek berkala · Selalu lamar di sumber aslinya', W / 2, footY + 34);
    ctx.textAlign = 'left';
}

function download(canvas, filename) {
    canvas.toBlob(blob => {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = filename || 'trakerja-loker.png';
        a.click();
        setTimeout(() => URL.revokeObjectURL(a.href), 2000);
    }, 'image/png');
}

window.TraKerjaPromo = { render, download };
