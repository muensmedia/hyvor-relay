# Environment Variables

This document applies to both package consumers and package maintainers.
In this repository, `.env.example` is only for local package development.

| Variable | Required | Default / Fallback | Used By | Description |
|---|---|---|---|---|
| `HYVOR_RELAY_ENDPOINT` | Yes | `https://relay.hyvor.com` | Console API + transport | Base URL of your Relay instance. |
| `HYVOR_RELAY_API_KEY_GENERAL` | Yes | `your-api-key-here` | Console API + transport fallback | Base key for all API usage unless `HYVOR_RELAY_API_KEY_SEND` and/or `HYVOR_RELAY_API_KEY_TRANSPORT` are explicitly set. |
| `HYVOR_RELAY_API_KEY_SEND` | No | `HYVOR_RELAY_API_KEY_GENERAL` | Console API sends endpoints | Key for `/sends` endpoints. |
| `HYVOR_RELAY_API_KEY_TRANSPORT` | No | `HYVOR_RELAY_API_KEY_GENERAL` | Laravel mail transport driver | Key for SMTP-like mail transport usage. |
| `HYVOR_RELAY_TIMEOUT` | No | `10` | Console API HTTP client | Request timeout in seconds. |
| `HYVOR_RELAY_CONNECT_TIMEOUT` | No | `5` | Console API HTTP client | Connect timeout in seconds. |
| `HYVOR_RELAY_WEBHOOK_SECRET` | No* | `null` | Webhook signature middleware | HMAC secret for `X-Signature` verification. |

\* Required only if you use incoming webhooks.

## Notes

- `HYVOR_RELAY_ENDPOINT` must be the base URL without `/api/...`.
- Example self-hosted endpoint: `https://relay.your-domain.tld`
