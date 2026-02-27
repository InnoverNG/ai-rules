# Project Rules for Claude

> Scaffolded by `innoverng/ai-rules` — customise for your project.

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
