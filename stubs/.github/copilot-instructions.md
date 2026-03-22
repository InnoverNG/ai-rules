# GitHub Copilot Instructions

> Scaffolded by `innoverng/ai-rules` — customise for your project.

## Workflow
- **Always discuss before implementing.** When given a task, first explain your understanding of the problem, propose an approach, and ask for approval before writing any code.
- **Break down complex tasks.** For multi-step work, outline each step and confirm the plan before proceeding.
- **Ask when uncertain.** If requirements are ambiguous, ask clarifying questions rather than making assumptions.
- **No surprises.** Never make changes beyond the agreed scope without flagging them first.

## Stack
- PHP 8.1+, Laravel 11+
- MySQL / PostgreSQL (prefer Eloquent ORM)
- Pest for testing

## Preferred Patterns
- Use Laravel helpers (`config()`, `cache()`, `dispatch()`) over static facades in services
- Prefer dependency injection over the `app()` helper inside classes
- Use enums for fixed value sets — avoid magic strings/integers
- Use DTOs or Value Objects to pass structured data between layers
- Use `readonly` classes for immutable data structures

## Avoid
- Raw SQL — use Eloquent or Query Builder
- Logic in Blade templates — keep views thin, move logic to View Composers or components
- Fat controllers — delegate to actions, services, or jobs
- `array_map` / `array_filter` — prefer Laravel collections (`collect()`)

## Naming Conventions
- Controllers: singular, suffixed (`UserController`)
- Models: singular (`User`, `Order`)
- Jobs: past-tense noun or verb phrase (`SendWelcomeEmail`, `ProcessPayment`)
- Events: past-tense (`UserRegistered`, `OrderShipped`)
- Listeners: present-tense action (`SendWelcomeNotification`)
