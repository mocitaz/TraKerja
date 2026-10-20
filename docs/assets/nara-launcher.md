# Nara avatar launcher

Latest user revision: remove the chat marker, outer ring, shadow and hover lift. The launcher is now only the existing 44px avatar within a transparent 52px button. The supplementary label and keyboard focus outline remain. The screenshots below document the preceding badge version, not this simpler revision.

Direction approved by the user: a 52px floating avatar, pale lavender background, small chat marker, and an Ask Nara label revealed on hover or keyboard focus. The previous dark rectangular experiment was rejected and reverted before this change. Existing avatar artwork is reused.

ENERGY 2 / RHYTHM 1 / MOTION 1. The avatar keeps Nara recognizable while reducing permanent screen coverage. The chat marker indicates the action; it does not claim online status or unread messages. The label explains the icon when focused. Fine-pointer hover raises the avatar 2px; no looping animation. Reduced-motion disables transitions and translation.

Delivery gate:

- Hard gate PASS: unchanged accessible button name, drawer relationship, focus outline and keyboard behavior. No fabricated availability claims or new assets. Mobile hit target is 52x52px and sits above navigation (bottom y=772 in an 844px viewport), with no horizontal overflow.
- Purpose PASS: one avatar focal point, lavender follows the product identity; small chat marker replaces the redundant full-size icon. The short label is supplementary and hidden from screen readers because the button already has a descriptive name.
- Liveliness PASS: agreed avatar composition and restrained hover motion match the declared dials; no new cards, glows or looping effects.
- Craft PASS: browser click opens Nara, Escape closes it and returns focus, Ask Nara appears on keyboard focus. Measured drawer.open=false after closing and launcher width=52. Dark mobile and light desktop inspected. Existing chat contract tests pass (8 tests/57 assertions); production build succeeds. No chat message was sent during verification.

Screenshots: `output/support-redesign/nara-avatar-desktop.jpg` and `output/support-redesign/nara-avatar-detail.jpg`.
