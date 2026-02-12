# Contributing

Thanks for contributing to `muensmedia/hyvor-relay`.

## Commit Message Schema

Use Conventional Commits with optional scopes:

```text
<type>(<scope>): <short summary>
```

Examples:

- `feat(webhooks): add middleware parameter for custom secret`
- `fix(actions): use send API key for sends endpoint`
- `docs(readme): refresh integration overview`
- `test(http): add invalid signature scenarios`

Common types: `feat`, `fix`, `refactor`, `docs`, `test`, `style`, `chore`.

## Development Setup (Docker)

Requirements:

- Docker + Docker Compose

Setup:

```bash
cp .env.example .env
docker compose up -d
docker compose exec php composer install
```

Run tests:

```bash
docker compose exec php php ./vendor/bin/pest
```

Run linter/formatter check:

```bash
docker compose exec php php ./vendor/bin/pint --test
```

Apply formatting:

```bash
docker compose exec php php ./vendor/bin/pint
```

## Development Setup (Local PHP)

Requirements:

- PHP `^8.5`
- Composer

Setup:

```bash
cp .env.example .env
composer install
```

Run tests:

```bash
php ./vendor/bin/pest
```

Run linter/formatter check:

```bash
php ./vendor/bin/pint --test
```

Apply formatting:

```bash
php ./vendor/bin/pint
```

## Pull Requests

- Keep PRs focused and small where possible.
- Add or update tests for behavioral changes.
- Update docs (`README.md` or `docs/*`) when public behavior/config changes.
