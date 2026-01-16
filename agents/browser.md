## Browser

This file exists to help you **use the browser effectively** to confirm UI and behavior changes/fixes.

### Non-negotiables

- Always verify the actual behavior in a real browser (not just “looks right” in code).
- When reporting results, provide **evidence**: what page you visited, what you clicked/typed, and what you observed (and any console errors).

### Quick workflow (every UI change)

- Load the relevant page and reproduce the scenario end-to-end.
- Open DevTools **Console** and confirm **no errors/warnings** caused by your change.
- Verify at least:
  - **Mobile + desktop** layout (responsive sanity check).
  - **Keyboard** navigation (Tab / Shift+Tab / Enter / Escape).
  - **Focus** behavior after actions (e.g. closing a modal returns focus to the trigger).
  - **Interactive states**: hover, active, focus, disabled, loading (when applicable).

### Capturing evidence

- **URL** (route/page) and the **exact steps** to reproduce.
- **Before/after** screenshots when visual changes are involved.
- If something breaks, include the **console error text** (copy/paste).
