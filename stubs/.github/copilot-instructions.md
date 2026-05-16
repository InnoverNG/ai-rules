# GitHub Copilot Instructions

> Scaffolded by `innoverng/ai-rules` — customise for your project.

## Stack

- Before starting any task, read `composer.json` and `composer.lock` to determine the exact PHP and Laravel versions in use. Never assume versions — always derive them from these files.
- PHP 8.1+, Laravel 11+, MySQL / PostgreSQL, Pest for testing.
- Use https://github.com/laravel/docs as the authoritative Laravel reference at all times.

## Anti-Hallucination

- **Verify before using.** Do not guess method names, class APIs, or helper functions. Check the codebase first.
- **Never invent.** If unsure whether a method, class, config key, or route exists — stop and ask.
- **Read existing code first.** Match the established pattern exactly before writing anything new.
- **No deprecated patterns.** If unsure about version availability, say so rather than guessing.

## Workflow

- **Always discuss before implementing.** Propose an approach and ask for approval before writing code.
- **Break down complex tasks.** Outline each step and confirm the plan before proceeding.
- **Ask when uncertain.** Clarify ambiguous requirements rather than assuming.
- **No surprises.** Never exceed the agreed scope without flagging it first.

## Code Style & Naming

- PSR-12; `declare(strict_types=1)` in every PHP file
- PHP 8.1+: use enums, readonly properties, union types
- Use descriptive names — avoid abbreviations
- Controllers: singular, suffixed — `UserController`
- Models: singular — `User`, `Order`
- Jobs: verb phrase — `SendWelcomeEmail`, `ProcessPayment`
- Events: past-tense — `UserRegistered`, `OrderShipped`
- Listeners: present-tense action — `SendWelcomeNotification`

## File Structure

- Feature-first: each feature has its own folder under `app/Http/Controllers/{Feature}/`
- Keep the folder flat: controller, service, and form request in the same folder
- Shared/cross-cutting logic lives in `app/Shared` only
- No global `app/Services`, `app/Actions`, or `app/Http/Requests` folders
- Routes in `routes/web.php` and `routes/api.php` only — no per-feature route files

## Framework Conventions

- Use Laravel helpers (`config()`, `cache()`, `dispatch()`) over static facades in services
- Prefer dependency injection over `app()` inside classes
- Use enums for fixed value sets — no magic strings/integers
- Use DTOs or Value Objects for structured data between layers
- Use `readonly` classes for immutable data structures
- Form Requests for all validation — keep controllers thin
- Eager-load relationships with `with()` — never allow N+1 queries

## Blade & Frontend

- No `@php` blocks or inline `<?php ?>` in Blade — logic belongs in controllers or ViewModels
- No `<style>` blocks in Blade files
- No inline `style=""` attributes — always use a CSS class
- All custom CSS in `public/custom/custom-{name}.css`, linked via:
  ```blade
  <link rel="stylesheet" href="{{ asset('custom/custom-{name}.css') }}">
  ```
- CSS classes use the `ui-` prefix and design system tokens (`var(--color-*)`, `var(--shadow-*)`)

## Livewire v3

- Store components in `app/Livewire/{Feature}/`; views in `resources/views/livewire/{feature}/`.
- Use `php artisan make:livewire` — never create component class and view manually.
- Component classes must be `final` with `declare(strict_types=1)`.
- All tracked state must be `public` typed properties. Use `#[Locked]` for browser-immutable properties.
- Use `#[Validate]` for simple static rules; use a `rules(): array` method for dynamic rules.
- Use `#[Computed]` for derived values — never compute in `render()` or the Blade template.
- Use `$this->dispatch()` for events (not `$this->emit()` — that is Livewire v2).
- Use `#[On('event-name')]` to listen for events — not the `$listeners` array.
- Keep action methods thin: validate → service → update state → dispatch event.
- Every Livewire Blade view must have a single root element.
- Use `wire:submit` on `<form>` — not `wire:click` on a submit button.
- Prefer deferred `wire:model` (form-submit binding) over `wire:model.live` to minimise round-trips.
- Use `wire:loading` / `wire:target` for user feedback during requests.
- Mark sensitive or tamper-proof properties `#[Locked]`; never store secrets in public properties.
- Test with `Livewire::test()` using Pest; assert state, output, dispatched events, and redirects.

## API Design

- Version all routes: `/api/v1/...`
- Return consistent JSON via API Resource classes — never raw arrays
- Correct HTTP status codes: `201` creates, `204` deletes, `422` validation errors

## Testing

- Pest for all tests; `tests/Feature` and `tests/Unit`
- `RefreshDatabase` for DB tests; model factories only, never raw inserts
- Every public method should have a corresponding test

## Security

- Validate all input at the Form Request layer
- Use Policies and Gates — no ad-hoc permission checks in controllers
- Never expose stack traces or internal errors in production responses

## Avoid

- Raw SQL — use Eloquent or Query Builder
- Logic in Blade — use ViewModels or View Composers
- Fat controllers — delegate to services or jobs
- `array_map` / `array_filter` — prefer `collect()`
- `<style>` blocks or inline `style=""` in Blade — use `public/custom/custom-*.css`
- Global app folders: `app/Services`, `app/Actions`, `app/Http/Requests`
- Hardcoded hex values or inline CSS — use design system tokens
