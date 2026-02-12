# Repository Guidelines

## Project Structure & Module Organization
This package is a Laravel mail transport library.

- `src/`: package source code (service provider, transport factory/api transport, controllers, enums).
- `tests/`: Pest test suite (`Transports/`, `Helpers/`, architecture tests, test mailables).
- `config/hyvor-relay.php`: publishable package configuration.
- `routes/api.php`: package route definitions.
- `.github/workflows/`: CI checks (`tests.yml`, `pint.yml`).
- `build/`: generated reports (JUnit, coverage output).

Keep production logic in `src/` and mirror test locations by feature area in `tests/`.

## Build, Test, and Development Commands
PHP runs in the Docker container for this repository. Execute PHP/Pest/Pint commands via `docker compose exec php ...` (not via a local `php` binary).

Primary workflow uses Composer scripts:

- `composer install`: install PHP dependencies.
- `composer test`: run Pest tests (`vendor/bin/pest`).
- `composer test-coverage`: run coverage with `phpunit.coverage.xml.dist`.
- `docker compose exec php php ./vendor/bin/pest --compact --profile`: CI-style local run.
- `docker compose exec php php ./vendor/bin/pint`: auto-fix formatting.
- `docker compose exec php php ./vendor/bin/pint --test`: formatting check only (matches CI).

If using the repo Docker setup:

- `docker compose up -d`
- `docker compose exec php composer install`
- `docker compose exec php php ./vendor/bin/pest`

## Coding Style & Naming Conventions
- PHP 8.5+, PSR-4 namespace: `Muensmedia\\HyvorRelay\\` (`src/`).
- Formatting is enforced with Laravel Pint (`pint.json`, Laravel preset).
- Use clear class names by responsibility, e.g. `HyvorRelayApiTransport`.
- Follow existing test naming: `*Test.php` with feature-specific folders.

Run Pint before opening a PR.

## Testing Guidelines
- Framework: Pest 4 with Laravel/Testbench plugins.
- Put tests under `tests/` and keep naming as `<UnitUnderTest>Test.php`.
- Add or update tests for every behavior change in `src/` (success, failure, and edge cases where relevant).
- Optional architecture guardrails are in `tests/ArchTest.php`.

## Commit & Pull Request Guidelines
Recent history follows Conventional Commit-style prefixes:

- `feat: ...`, `fix: ...`, `refactor(test): ...`, `ci: ...`, `chore: ...`

Use short imperative summaries and keep scope precise (e.g. `ci: run pint on PRs`).

For PRs targeting `main`:
- Ensure `pest` and `pint --test` pass locally.
- Include a concise description, rationale, and test notes.
- Link related issues/tasks when applicable.
