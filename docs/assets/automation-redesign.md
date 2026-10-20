# Automation redesign

## Design read before implementation

User direction: simpler, professional Automation page influenced by TraKerja's landing page and Dashboard, with fewer containers. ENERGY 2 / RHYTHM 3 / MOTION 1. Use the existing workspace frame, typography, white/off-white surfaces and restrained purple action color. Dark mode uses neutral gray surfaces. Keep one labelled extension example as the visual focal point; it is not a live connection or saved record. Supported platform marks and the existing profession avatar identify real product concepts, rather than introducing new decorative assets.

Replace the category tabs and multiple nested cards with an open extension introduction, a horizontal four-step installation guide and one combined data workspace. Existing CSV access rules, server validation, preview, error/success feedback and download endpoints remain. The CSV query parameter and post-import/error navigation should scroll to the visible data section rather than hiding the other feature. Counts keep their existing owner-scoped meaning and must not imply a connected browser.

## Implemented direction and delivery gate

PASS. The hero remains open with one labelled example. After the user's feedback that the guide was too plain, installation uses one shared surface, connected numbered steps and small folder/Chrome-setting illustrations instead of four separate cards. The setting illustration is hidden from accessibility APIs and is not an interactive switch. Neutral dark surfaces retain readable text. Header measurements match Applications at the same desktop viewport: x40/y118, 26px, weight600; description spacing is 6px. The page uses the same shared responsive gutter.

Verification: production asset build passed (82 modules); 44 feature tests passed with 184 assertions covering CSV round trips/validation, access gating, legacy deep links, extension security and workspace navigation. In a separate synthetic SQLite preview, copied the Chrome address successfully; uploaded a two-row CSV and observed one valid/one skipped row, then confirmed import and received the matching success summary. Fixed preview date serialization so the displayed calendar date matches the CSV, with a regression assertion. Desktop/light, neutral dark and 390px mobile were visually checked; native button/link controls retain focus styles, coarse-pointer sizing and reduced-motion support. Real production data and browser extension settings were not changed.

Screenshots: `output/automation-redesign/desktop.png`, `mobile.png`, `installation.png`. Existing avatar/platform assets reused; no generated assets. No fake totals or implied extension connection. Deployment is not part of this local redesign.
