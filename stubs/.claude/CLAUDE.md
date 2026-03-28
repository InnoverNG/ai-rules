# Project Rules for Claude

> Scaffolded by `innoverng/ai-rules` — customise for your project.

## Stack

- Before starting any task, read `composer.json` and `composer.lock` to determine the exact PHP and Laravel (and other package) versions in use. Never assume versions — always derive them from these files.
- Only use APIs, methods, and patterns that exist in those exact versions.
- Use https://github.com/laravel/docs as the authoritative Laravel reference at all times.
- The `docs` folder contains project-specific context. Read it before starting any task.

## Anti-Hallucination

- **Verify before using.** Do not guess method names, class APIs, or helper functions. Check the codebase or official Laravel docs before using anything you are not certain exists.
- **Never invent.** If you are unsure whether a method, class, config key, or route exists — stop and ask rather than assuming.
- **Read existing code first.** Before writing anything new, check sibling files for the established pattern. Match it exactly.
- **No deprecated patterns.** If you are unsure whether a feature is available in the project's Laravel/PHP version, say so rather than guessing.

## Workflow

- **Always discuss before implementing.** When given a task, first explain your understanding of the problem, propose an approach, and ask for approval before writing any code.
- **Break down complex tasks.** For multi-step work, outline each step and confirm the plan before proceeding.
- **Ask when uncertain.** If requirements are ambiguous, ask clarifying questions rather than making assumptions.
- **No surprises.** Never make changes beyond the agreed scope without flagging them first.

## Code Style & Naming

- PHP 8.1+ — use enums, readonly properties, and union types where appropriate
- Follow PSR-12; `declare(strict_types=1)` in every PHP file
- Use descriptive variable and method names — avoid abbreviations
- Controllers: singular, suffixed — `UserController`
- Models: singular — `User`, `Order`
- Jobs: verb phrase — `SendWelcomeEmail`, `ProcessPayment`
- Events: past-tense — `UserRegistered`, `OrderShipped`
- Listeners: present-tense action — `SendWelcomeNotification`

## File Structure

- Feature-first: each feature has its own folder under `app/Http/Controllers/{Feature}/`
- Keep the folder flat: controller, service, and form request in the same folder
- Sub-folders only when a feature exceeds 5–6 files of the same type, and only inside that feature folder
- Shared/cross-cutting logic lives in `app/Shared` only
- Do NOT create global `app/Services`, `app/Actions`, or `app/Http/Requests` folders
- Routes live in `routes/web.php` and `routes/api.php` only — no per-feature route files

## Framework Conventions

- Laravel service providers for all bootstrapping
- Repository pattern for data access; bind interfaces in a service provider
- Form Requests for all validation — keep controllers thin
- Controllers and models should be `final` classes
- Use Eloquent relationships; avoid N+1 with eager loading (`with()`)
- Prefer queued jobs for anything that does not need to be synchronous
- Use enums for fixed value sets — avoid magic strings/integers
- Prefer dependency injection over the `app()` helper inside classes

## Blade & Frontend

- **Never** use `@php` / `@endphp` or inline `<?php ?>` in Blade for logic.
- Blade is for presentation only (`@if`, `@foreach`, `{{ }}`). Compute in controllers, models, helpers, or ViewModels.
- **Never** write `<style>` blocks inside Blade files. Never use inline `style=""` attributes.
- All custom CSS lives in `public/custom/custom-{name}.css`. Link it via the `asset()` helper in the layout's styles slot:
  ```blade
  <link rel="stylesheet" href="{{ asset('custom/custom-{name}.css') }}">
  ```
- CSS classes use the `ui-` prefix and design system tokens (`var(--color-*)`, `var(--shadow-*)`).

## API Design

- Version all API routes: `/api/v1/...`
- Return consistent JSON via API Resource classes — never raw arrays
- Use correct HTTP status codes: `201` for creates, `204` for deletes, `422` for validation errors

## Testing

- Pest for all tests; feature tests in `tests/Feature`, unit tests in `tests/Unit`
- Do not use Laravel Dusk unless explicitly required
- Use `RefreshDatabase` for database tests; use model factories, never raw inserts
- Every public method should have a corresponding test

## Security

- Never trust user input — validate at the Form Request layer
- Use Laravel's built-in authorization (Policies, Gates) — no ad-hoc permission checks
- Never expose stack traces or internal errors in production responses

## Avoid

- Raw SQL — use Eloquent or Query Builder
- Logic in Blade templates — use ViewModels or View Composers
- Fat controllers — delegate to services or jobs
- `array_map` / `array_filter` — prefer Laravel collections (`collect()`)
- `<style>` blocks or inline `style=""` in Blade — use `public/custom/custom-*.css` instead
- Global app folders: `app/Services`, `app/Actions`, `app/Http/Requests`
- Hardcoded hex values or inline CSS in components — use design system tokens
