# Agent instructions — Bitrix project

This repository is a **1C-Bitrix: Site Management** (Bitrix Framework **≥ 25.750.0**) site. Automated and assisted coding agents should follow this document together with workspace Cursor rules (`.cursor/rules/`) when present. When instructions conflict, apply the **narrowest** rule for the task, then **project-specific** rules over generic advice.

## Role and priorities

- Prefer **Bitrix-native APIs**: `main` / module classes, **D7** (`Bitrix\Main\*`), ORM tables, `Main\EventManager`, components, and documented extension points.
- Keep changes **scoped** to the task; match existing patterns (naming, file layout, PHP style, how components and `local/` code are organized).
- Use **`use` imports** for PHP classes where applicable; avoid parallel mini-frameworks inside the project.

## Where to put code

| Concern | Typical location |
|--------|------------------|
| Init, agents, events, includes | `local/php_interface/` (e.g. `init.php`, `include/`) |
| Custom components | `local/components/<namespace>/` |
| Site template(s) | `local/templates/` |
| Custom / local modules | `local/modules/<vendor>.<name>/` |
| Public entry | `index.php`, `urlrewrite.php` (if tracked) at site root |
| Core and stock modules | `bitrix/` (often not in VCS or partially tracked — follow team policy) |

Adjust paths if this copy uses symlinks or deploys `bitrix/` outside the repo.

## Bitrix conventions

- Use **events** and supported APIs instead of patching core; do not fork `bitrix/modules/` for routine customization.
- **Component templates**: do not manually `include` `script.js` / `style.css` where the kernel loads them automatically; follow the same pattern as neighbouring templates in this project.
- **Caching**: be explicit when adding cache dependencies; respect `TaggedCache` / cache invalidation patterns used elsewhere in the codebase.
- **Security**: escape output in templates; validate input; use CSRF where the API requires it.

## Documentation

- Public API and guides: [Bitrix for developers](https://dev.1c-bitrix.ru/api_help/).
- Align with the **Bitrix Framework version** in production (this project targets **≥ 25.750.0**).

## Git and delivery

- **Commits:** English messages; [**Conventional Commits**](https://www.conventionalcommits.org/en/v1.0.0/) (`feat:`, `fix:`, `chore:`, `refactor:`, etc.).
- **Secrets:** never commit `bitrix/.settings.php`, `bitrix/php_interface/dbconn.php`, or real credentials; respect `.gitignore`.
- Prefer **small, reviewable diffs**; avoid unrelated refactors or repo-wide formatting unless requested.

## Out of scope unless explicitly requested

- Broad refactors unrelated to the task.
- Extra top-level documentation files beyond what maintainers ask for.
- Changes that complicate Bitrix updates or bypass supported extension points without justification.

When in doubt, stay consistent with neighbouring code and official documentation for the installed edition and version.
