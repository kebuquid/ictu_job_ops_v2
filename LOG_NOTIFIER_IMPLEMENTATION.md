# Log Viewer Enhancement - Implementation Summary

## .env Configuration

Add the following variables to your `.env` file to configure the log notification system:

```env
# ============================================
# Log Notifier Configuration
# ============================================

# Enable/disable the notification system globally
LOG_NOTIFIER_ENABLED=true

# Comma-separated list of notification channels to use
# Available channels: email, telegram, slack
LOG_NOTIFIER_CHANNELS=email

# ⚠️ EMAIL: Already configured via Email config!
# The system uses your existing email.fromEmail and email.fromName
# Only set these if you want to OVERRIDE the defaults:
# LOG_NOTIFIER_EMAIL_FROM=custom@yourdomain.com
# LOG_NOTIFIER_EMAIL_FROM_NAME=Custom Alert Name

# Telegram Bot Token (leave empty if not using Telegram)
# Get this from BotFather on Telegram (@botfather)
LOG_NOTIFIER_TELEGRAM_BOT_TOKEN=

# Telegram Chat ID where alerts will be sent
# Use @myidbot to get your numeric chat ID
LOG_NOTIFIER_TELEGRAM_CHAT_ID=

# Slack Webhook URL (leave empty if not using Slack)
# Generate this in your Slack app settings > Incoming Webhooks
LOG_NOTIFIER_SLACK_WEBHOOK_URL=

# Deduplication time-to-live in seconds
# Prevents duplicate alerts for the same error within this window
LOG_NOTIFIER_DEDUP_TTL=300

# Base URL to the log viewer (for CTA button in emails)
LOG_NOTIFIER_BASE_URL=https://yourdomain.com/logs/view/
```

**Note:** 
- **Email recipients** are automatically queried from the database (role_id = 3, MIS section)
- **Email settings** are automatically inherited from Email config (fromEmail, fromName, SMTP)
- Only configure LOG_NOTIFIER_EMAIL_FROM if you want to override the default
- See `app/Config/LogNotifier.php` for details

---

## Integration Checklist

### Part 1: Installation ✓

- [x] Created `app/Config/LogNotifier.php` — Main configuration class
- [x] Created `app/Libraries/LogNotifier.php` — Multi-channel notification engine
- [x] Created `app/Libraries/NotifyingLogger.php` — Custom CI4 logger wrapper
- [x] Updated `app/Config/Services.php` — Registered custom logger service
- [x] Created `app/Views/emails/log_alert.php` — HTML email template
- [x] Enhanced `app/Views/logs/index.php` — UI improvements and controls
- [x] Added methods to `app/Controllers/Admin/LogViewerController.php` — `testNotify()` and `notificationSettings()`
- [x] Updated `app/Config/Routes.php` — Added new routes

---

## Testing the Notification System

### 1. Prerequisites

- SMTP credentials configured in `app/Config/Email.php` (if using email):
  - `SMTPHost` — Your SMTP server
  - `SMTPUser` — SMTP username
  - `SMTPPass` — SMTP password
  - `SMTPPort` — Usually 587 (TLS) or 465 (SSL)
  - `SMTPCrypto` — Set to 'tls' or 'ssl'
  - `fromEmail` — Valid sender email

- At least one user exists in the database with:
  - `role_id = 3` (ICTU Staff)
  - In the MIS section
  - Valid email address

### 2. Configure .env

Update your `.env` file with:
```env
LOG_NOTIFIER_ENABLED=true
LOG_NOTIFIER_CHANNELS=email
LOG_NOTIFIER_EMAIL_FROM=noreply@yourdomain.com
LOG_NOTIFIER_EMAIL_FROM_NAME=MyApp Alerts
LOG_NOTIFIER_DEDUP_TTL=300
LOG_NOTIFIER_BASE_URL=https://yourdomain.com/logs/view/
```

**Note:** Email recipients are automatically fetched from the users table where `role_id = 3` and section is "MIS" — no manual configuration needed!

### 3. Send a Test Alert (Super Admin UI)

1. Navigate to the **Logs** page as super admin
2. Look for the **"Test Alert"** button in the toolbar (next to Download button)
3. Click "Test Alert" — you should see a confirmation toast
4. Check email for MIS staff to receive the test alert

The test alert will:
- Be sent through all configured channels (email, Telegram, Slack)
- Show up in the log viewer metrics
- Trigger notifications for all MIS staff with valid emails

### 4. Test Email Channel

**Email recipients are automatically populated from the database:**
- Fetches all users with `role_id = 3` (ICTU Staff)
- Filters for those in the MIS section
- Validates email addresses
- Updates dynamically as users are added/removed

**To Test:**
1. Ensure at least one user with role_id = 3 in MIS section has a valid email
2. Click "Test Alert" in the logs UI
3. Check that user's email inbox (and spam folder)
4. Email should have:
   - Color-coded header (red for ERROR)
   - Full log message
   - Environment badge (green for dev, red for production)
   - Timestamp and metadata
   - "View Log File" button
   - System info footer

### 5. Test Telegram Channel (Optional)

**Prerequisites:**
1. Create a Telegram bot via BotFather:
   - Message `@botfather` on Telegram
   - Follow `/newbot` command
   - Copy the **Bot Token**

2. Get your Chat ID:
   - Message `@myidbot` on Telegram
   - It will respond with your numeric ID (e.g., `123456789`)

**To Test:**
1. Set `.env`:
   ```env
   LOG_NOTIFIER_CHANNELS=email,telegram
   LOG_NOTIFIER_TELEGRAM_BOT_TOKEN=<your-bot-token>
   LOG_NOTIFIER_TELEGRAM_CHAT_ID=<your-numeric-id>
   ```

2. Clear cached config: `php spark cache:clear`

3. Click "Test Alert" in the logs UI

4. Check Telegram — you should receive a formatted message with:
   - Alert level (ERROR, CRITICAL, etc.)
   - Full message text
   - Timestamp
   - Environment

### 6. Test Slack Channel (Optional)

**Prerequisites:**
1. Create an Incoming Webhook in your Slack workspace:
   - Go to your Slack app settings
   - Navigate to **Incoming Webhooks**
   - Click **Add New Webhook to Workspace**
   - Select target channel
   - Copy the **Webhook URL**

**To Test:**
1. Set `.env`:
   ```env
   LOG_NOTIFIER_CHANNELS=email,telegram,slack
   LOG_NOTIFIER_SLACK_WEBHOOK_URL=<your-webhook-url>
   ```

2. Clear cache: `php spark cache:clear`

3. Click "Test Alert"

4. Check Slack — you should see an embedded alert card with:
   - Color-coded header (red/blue based on severity)
   - Title and message
   - Timestamp and environment

---

## Testing Error Notifications in Production

The system automatically triggers notifications when these log levels are written:
- `ERROR`
- `CRITICAL`
- `EMERGENCY`
- `ALERT`

These levels **do NOT** trigger notifications:
- `WARNING`
- `NOTICE`
- `INFO`
- `DEBUG`

### Manually Trigger an Alert

```php
// In any controller or service:
log_message('error', 'This is a test error — will trigger notifications');
```

This will:
1. Write to the log file
2. Trigger notifications via all configured channels
3. Apply deduplication (same error won't be notified again for 5 minutes by default)

---

## Notification Status Indicator

In the Logs UI (super admin only), you'll see a status badge showing:
- **Green "Notifications: ON"** — System is enabled
- **Red "Notifications: OFF"** — System is disabled
- Lists active channels in parentheses: `(email, telegram)`

Click the status badge tooltip to see full configuration (without exposing sensitive tokens).

---

## Deduplication

The system prevents notification spam by hashing the log level + first line of the message and caching it for `LOG_NOTIFIER_DEDUP_TTL` seconds (default 300s = 5 minutes).

This means:
- Same error appearing again within 5 minutes = no notification
- Different first-line message or level = will notify
- After 5 minutes, same error will notify again

Customize TTL in `.env`:
```env
LOG_NOTIFIER_DEDUP_TTL=600  # 10 minutes
```

---

## Email Template Variables

The email template expects these variables (automatically passed by NotifyingLogger):

| Variable | Type | Example |
|----------|------|---------|
| `$level` | string | "ERROR", "CRITICAL", "EMERGENCY", "ALERT" |
| `$message` | string | Full multi-line log message |
| `$timestamp` | string | "April 8, 2026 2:30 PM" |
| `$environment` | string | "production", "development" |
| `$appName` | string | From APP_NAME env or config |
| `$logViewerUrl` | string | URL to log file in viewer |
| `$triggeredBy` | string\|null | "app/Controllers/MyController.php:123" |

All output is HTML-escaped for security.

---

## Troubleshooting

### Notifications Not Sending?

1. **Check if enabled:**
   ```bash
   php spark tinker
   >>> config('LogNotifier')->enabled
   # Should return: true
   ```

2. **Check cache (deduplication):**
   ```bash
   php spark cache:clear  # Clear all cache
   ```

3. **Review logs:**
   ```bash
   tail -f writable/logs/log-*.log | grep LogNotifier
   ```

4. **Test email specifically:**
   ```php
   $email = service('email');
   $email->setTo('test@example.com');
   $email->setSubject('Test');
   $email->setMessage('<h1>Hello</h1>');
   var_dump($email->send());
   ```

### Email Not Received?

- Check spam folder
- Verify `LOG_NOTIFIER_EMAIL_RECIPIENTS` has valid emails
- Confirm SMTP credentials in `app/Config/Email.php`
- Check `writable/logs/` for SMTP errors

### Telegram Not Receiving?

- Verify bot token and chat ID are correct
- Message the bot first (`/start` command)
- Check firewall — ensure outbound HTTPS allowed

### Slack Not Receiving?

- Verify webhook URL is correct
- Test webhook manually with cURL:
  ```bash
  curl -X POST -H 'Content-type: application/json' \
    --data '{"text":"Test"}' \
    YOUR_WEBHOOK_URL
  ```

---

## Advanced Configuration

### Disable for Specific Log Levels

Edit `app/Config/LogNotifier.php`:

```php
public array $notifyOnLevels = ['error', 'critical'];  // Only these

// Or to be very strict:
public array $notifyOnLevels = ['emergency'];  // Only emergencies
```

### Selective Recipients per Environment

```php
public function __construct()
{
    parent::__construct();
    
    if (ENVIRONMENT === 'production') {
        $this->emailRecipients = env('PROD_ALERT_EMAILS', '');
    } else {
        $this->emailRecipients = env('DEV_ALERT_EMAILS', '');
    }
}
```

---

## Performance Considerations

- **Email sending is synchronous** — Slow SMTP will block the request
- **No queue system** — For high-volume sites, consider adding a job queue
- **Cache TTL for deduplication** — Uses CI4's file/redis cache (configurable)

To make notifications async (advanced):
1. Log the critical error normally
2. Fire background job/webhook
3. Send notification from worker

Example:
```php
// In NotifyingLogger::log()
queue_job('send_notification', ['level' => $level, 'message' => $message]);
```

---

## Security Notes

- Sensitive tokens (telegram, Slack) are **masked** in the notification settings view
- Email recipients list is only visible to super admins
- All HTML output in email template is **escaped**
- CSRF protection on POST endpoints (automatically handled)
- Super admin role required for test/settings endpoints

---

## File Locations Reference

| File | Purpose |
|------|---------|
| `app/Config/LogNotifier.php` | Configuration class |
| `app/Libraries/LogNotifier.php` | Multi-channel notification engine |
| `app/Libraries/NotifyingLogger.php` | CI4 Logger wrapper |
| `app/Config/Services.php` | Custom logger service registration |
| `app/Views/emails/log_alert.php` | HTML email template |
| `app/Views/logs/index.php` | Enhanced log viewer UI |
| `app/Controllers/Admin/LogViewerController.php` | New methods: `testNotify()`, `notificationSettings()` |
| `app/Config/Routes.php` | New routes: `logs/test-notify`, `logs/settings` |

---

## Next Steps

1. ✓ Copy .env snippet to your `.env` file
2. ✓ Configure SMTP in `app/Config/Email.php` (if using email)
3. ✓ Run `php spark cache:clear` to refresh config
4. ✓ Visit Logs page as super admin
5. ✓ Click "Test Alert" button
6. ✓ Verify email/Telegram/Slack receives the alert
7. ✓ Monitor `writable/logs/` for any errors

Enjoy your enhanced log monitoring! 🎉
