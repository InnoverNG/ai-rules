# Project Rules for Claude

> Scaffolded by `innoverng/ai-rules` — customise for your project.

## Stack
- Before starting any task, read `composer.json` and `composer.lock` to determine the exact PHP and Laravel (and other package) versions in use. Never assume versions — always derive them from these files.
- Only use APIs, methods, and patterns that exist in those exact versions.

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

## Code Style
- PHP 8.1+ — use enums, readonly properties, fibers, and intersection types where appropriate
- Follow PSR-12; `declare(strict_types=1)` in every PHP file
- Use descriptive variable and method names — avoid abbreviations

## Framework Conventions
- Laravel service providers for all bootstrapping
- Repository pattern for data access; bind interfaces in a service provider
- Form Requests for validation — keep controllers thin
- Use Eloquent relationships; avoid N+1 with eager loading (`with()`)
- Prefer queued jobs for anything that does not need to be synchronous

## Blade / views
- **Never** use `@php` / `@endphp` or inline `<?php ?>` in Blade for logic.
- Compute in the **controller**, **model**, **helper**, or ViewModel; pass ready-to-use variables to the view. Blade is for presentation (`@if`, `@foreach`, `{{ }}`), not for building data.

## API Design
- Version all API routes (`/api/v1/...`)
- Return consistent JSON via API Resource classes
- Use HTTP status codes correctly (201 for creates, 204 for deletes, etc.)

## Testing
- Pest for all tests; feature tests live in `tests/Feature`, unit tests in `tests/Unit`
- Every public method should have a corresponding test
- Use `RefreshDatabase` for database tests; use model factories, never raw inserts

## Security
- Never trust user input — validate at the Form Request layer
- Use Laravel's built-in authorization (Policies, Gates) — no ad-hoc permission checks
- Never expose stack traces or internal errors in production responses
