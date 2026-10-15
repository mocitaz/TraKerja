# Telegram admin commands

Incoming admin commands now fail closed. Outgoing notifications keep their existing configuration.

## Deployment configuration

Set these values through the deployment secret/configuration system, without committing credentials:

| Variable | Purpose |
| --- | --- |
| `TELEGRAM_WEBHOOK_SECRET` | A new random secret, separate from the bot token. Telegram accepts 1–256 ASCII letters, digits, underscores and hyphens; use at least 32 random characters. |
| `TELEGRAM_ALLOWED_USER_IDS` | Comma-separated numeric Telegram **user IDs** of authorized administrators. Usernames and display names are not identities. |
| `TELEGRAM_ALLOWED_CHAT_IDS` | Comma-separated numeric IDs of permitted private chats or groups. Group IDs can be negative. Both sender and chat must be allowed. |

1. Configure the allowlists using IDs verified through a trusted admin account. The notification channel ID is not automatically authorized for commands.
2. For webhook delivery, update Telegram's `setWebhook` registration for `/api/telegram/webhook`, supplying the same new value as `secret_token`. Telegram then sends `X-Telegram-Bot-Api-Secret-Token`. Do not place the secret in the webhook URL or log registration credentials.
3. Rebuild Laravel's configuration cache as part of deployment and restart long-running polling workers so they load the new settings.
4. Verify an authorized `/help` command in the approved chat. Verify a missing/wrong webhook header receives 403, and an unapproved user/chat produces no command execution or reply.

An absent or invalid webhook secret returns 503. Empty allowlists reject all commands. Channel posts, anonymous group administrators (`sender_chat`), bots and malformed messages cannot execute commands. Administrators must send commands as their own allowlisted users.

## Limits and acknowledgements

- After secret authentication, the webhook accepts at most 60 requests per minute through one shared cache bucket, independent of client or forwarded IP. Excess requests return 429 with `Retry-After`.
- Authorized commands have a separate 10-per-minute budget per sender, shared across approved chats and webhook/polling delivery.
- Valid webhook deliveries containing unauthorized, unsupported or sender-limited commands are acknowledged with 200 without executing the command, avoiding repeated Telegram delivery of rejected updates.
- Use a shared persistent cache for all web and polling workers so the limits apply across processes/servers. An in-memory array cache only limits the current process.
- These cache-based throttles are approximate under simultaneous requests; they are not strict concurrency limits.

Polling through `telegram:poll` does not need a webhook secret, but enforces the same sender/chat allowlists and sender budget. Do not run webhook and polling delivery simultaneously.

## Regression checks

`tests/Feature/TelegramWebhookSecurityTest.php` boots without a local environment file, uses synthetic credentials and an array cache, prevents stray HTTP requests, and requires no database migrations. It covers authentication, unauthorized commands, malformed/bot/channel messages, approved execution, shared budgets, and cooldown recovery.
