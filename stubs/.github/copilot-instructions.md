# GitHub Copilot Instructions

> Scaffolded by `innoverng/ai-rules` — customise for your project.

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
