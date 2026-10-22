# TraKerja admin sidebar

Status: REVERTED at the user's request. The neutral full-height sidebar, original menu icons and profile position at the bottom are restored. First-name-only display and removal of the profile gear/subtitle remain, along with the prior removal of language, theme and User workspace controls. The glass review below describes the discarded iteration, not the current design.

Direction: the supplied translucent sidebar references, adapted to TraKerja's existing compact navigation. Energy 2, rhythm 2, motion 1. A single frosted panel uses blue-grey translucency, backdrop blur and a thin edge to separate navigation from the content canvas. Inter and the existing Phosphor regular icons make menu labels and symbols lighter. Existing TraKerja branding remains. No service integrations or task features were added.

Profile sits below the brand with the existing avatar and first name only. The gear and visible Profile settings subtitle are removed; the anchor retains its accessible profile label and destination. Sign out remains at the bottom. Overview/Operations grouping and real admin routes are preserved. Language/theme controls and User workspace remain absent.

The glass background initially blended into the page because the global light/dark theme rules reset body background-image. The admin-only background selector now overrides that reset, while the main content column keeps its original solid canvas. The sidebar has a visible blue-grey wash, translucent surface and blur, rather than a nearly white panel. Sidebar secondary text has its own darker light-theme color to retain contrast over the tint.

## Delivery gate

- Hard gate PASS: source and browser checks confirm first-name text, no gear/subtitle, no removed utility controls, and real profile/navigation/logout actions. No fabricated data or assets. No horizontal overflow at 1440 or 320 px. Mobile drawer fits at x10/y10, 280x720 within 320x740. Labels stay visible on mobile even when desktop collapse is saved.
- Purpose gate PASS: glass, tint and blur are explicitly requested and limited to the sidebar. The narrow edge and shallow shadow distinguish the floating navigation; blue identifies the current route. Regular icons map to the existing menu functions. Group separators and profile identity provide structure without additional cards or menus.
- Liveliness PASS: energy 2, rhythm 2, motion 1. The active route is the focal point; profile identity, menu groups and the separate sign-out area provide rhythm. Existing restrained width/drawer transitions respect reduced motion.
- Craftsmanship PASS: desktop expanded/collapsed and narrow mobile inspected, including light and dark themes. Collapse produces a 68 px icon panel; the drawer uses a scrollable navigation region. Escape closes the mobile drawer and restores focus. No browser console errors. Nine relevant Laravel tests passed (69 assertions); Vite production build and whitespace checks passed. Browser work used the isolated QA database and cookie, without changing main records or sending email.
