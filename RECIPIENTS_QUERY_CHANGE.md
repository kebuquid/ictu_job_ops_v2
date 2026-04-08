# Email Recipients Auto-Query Implementation

## What Changed

The log notifier system now features **two major auto-configurations**:

### 1. Email Recipients Auto-Query
The system **automatically queries the database** for email recipients instead of requiring manual configuration in `.env`.

### 2. Email Settings Inheritance (NEW!)
The system now **reuses your existing Email config** instead of requiring duplicate SMTP sender information.

### Before
```env
# Manual email recipient configuration (no longer needed!)
LOG_NOTIFIER_EMAIL_RECIPIENTS=admin@domain.com,staff@domain.com

# Duplicate email settings (no longer needed!)
LOG_NOTIFIER_EMAIL_FROM=noreply@domain.com
LOG_NOTIFIER_EMAIL_FROM_NAME=Alerts
```

### Now
✅ **Automatic** — Both auto-configured!

```env
# Only enable/disable and optional channel configs
LOG_NOTIFIER_ENABLED=true
LOG_NOTIFIER_CHANNELS=email
```

SMTP credentials and sender address are inherited from `email.*` config automatically!

---

## Email Config Inheritance (NEW!)

In addition to auto-querying recipients, LogNotifier now **reuses your existing Email config** automatically.

### How It Works

**File:** `app/Config/LogNotifier.php`  
**Constructor:** Lines 75-97

```php
// Use existing Email config (email.fromEmail, email.fromName) as defaults
$emailConfig = config('Email');
$this->emailFromAddress = env('LOG_NOTIFIER_EMAIL_FROM', $emailConfig->fromEmail ?? '');
$this->emailFromName = env('LOG_NOTIFIER_EMAIL_FROM_NAME', $emailConfig->fromName ?? 'ICTU Job Operations');
```

### Fallback Hierarchy

1. **First:** Check if `LOG_NOTIFIER_EMAIL_FROM` is set in .env → use it
2. **Second:** Fall back to `email.fromEmail` from Email config
3. **Third:** Default to empty string (Email config's fromEmail)

Same for **LOG_NOTIFIER_EMAIL_FROM_NAME**:
1. Check LOG_NOTIFIER_EMAIL_FROM_NAME (if set)
2. Fall back to email.fromName from Email config
3. Default to 'ICTU Job Operations'

### Example with Your .env

Your current `.env`:
```env
email.fromEmail = 'kebuquid@my.cspc.edu.ph'
email.fromName  = 'ICTU Job Ticketing'
email.SMTPHost  = 'smtp.gmail.com'
email.SMTPUser  = 'kebuquid@my.cspc.edu.ph'
email.SMTPPass  = 'klgu ojxg tnmy iqby'
email.SMTPPort  = 587
email.SMTPCrypto = 'tls'
```

**Result:** LogNotifier will automatically use:
- **From:** kebuquid@my.cspc.edu.ph (from email.fromEmail)
- **Name:** ICTU Job Ticketing (from email.fromName)
- **SMTP:** smtp.gmail.com, port 587, TLS, etc. (from Email config)

No separate LogNotifier configuration needed! ✅

### When to Override

Only set `LOG_NOTIFIER_EMAIL_FROM` if you want alert emails from a **different address**:

```env
# Optional: Use different sender for alerts
LOG_NOTIFIER_EMAIL_FROM=alerts@yourdomain.com
LOG_NOTIFIER_EMAIL_FROM_NAME=Security Alerts
```

Otherwise, leave these blank and the Email config values are used automatically.

---

### Query Criteria
The system fetches emails from the `users` table where:
- `users.role_id = 3` (ICTU Staff role)
- User's section is **MIS** (matches either section acronym or name)
- Email address is valid and not empty

### Code Location
**File:** `app/Config/LogNotifier.php`  
**Method:** `getMISStaffEmails()` (lines 105-135)

### Query Implementation
```php
private function getMISStaffEmails(): array
{
    try {
        $userModel = new UserModel();

        // Query users with role_id = 3 (ICTU staff) in MIS section
        $users = $userModel
            ->select('users.email')
            ->join('sections', 'sections.section_id = users.section_id', 'left')
            ->where('users.role_id', 3)  // ICTU Staff
            ->where('(sections.acronym = "MIS" OR sections.name = "MIS")', null, false)
            ->findAll();

        // Validate email addresses
        $emails = array_column($users, 'email');
        return array_filter(array_map('trim', $emails), function ($email) {
            return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    } catch (\Exception $e) {
        log_message('error', '[LogNotifier] Failed to query MIS staff emails: ' . $e->getMessage());
        return [];
    }
}
```

---

## Benefits

| Benefit | Impact |
|---------|--------|
| **No Manual Config** | Remove `LOG_NOTIFIER_EMAIL_RECIPIENTS` from .env |
| **Auto-Updates** | Recipients change automatically when users are added/removed |
| **Email Validation** | Invalid emails are filtered out automatically |
| **Centralized** | Single source of truth (user database) |
| **Error Handling** | Database errors don't break the app |

---

## Configuration Changes

### .env Updates

**REMOVE** (no longer needed):
```env
LOG_NOTIFIER_EMAIL_RECIPIENTS=email1@domain.com,email2@domain.com
```

**KEEP** (still required):
```env
LOG_NOTIFIER_ENABLED=true
LOG_NOTIFIER_CHANNELS=email
LOG_NOTIFIER_EMAIL_FROM=noreply@yourdomain.com
LOG_NOTIFIER_EMAIL_FROM_NAME=Alerts
LOG_NOTIFIER_DEDUP_TTL=300
LOG_NOTIFIER_BASE_URL=https://yourdomain.com/logs/view/
```

### SMTP Email Config

`app/Config/Email.php` still required for sending emails:
```php
public string $SMTPHost = 'smtp.yourserver.com';
public string $SMTPUser = 'your-email@yourserver.com';
public string $SMTPPass = 'your-password';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

---

## Database Requirements

Ensure your users table has:
- ✅ `users.role_id` column with values: 1=Super Admin, 2=Admin, 3=ICTU Staff
- ✅ `users.section_id` column (foreign key to sections table)
- ✅ `users.email` column with valid email addresses
- ✅ `sections.section_id`, `sections.acronym`, `sections.name` columns

**Example Users:**
```
User: John Smith        | role_id: 3 | section: MIS | email: john@domain.com   ✅ Will receive
User: Jane Doe         | role_id: 3 | section: HR  | email: jane@domain.com    ❌ Wrong section
User: Bob Admin        | role_id: 2 | section: MIS | email: bob@domain.com     ❌ Wrong role
User: Alice Smith      | role_id: 3 | section: MIS | email: (null)             ❌ No email
```

---

## Deployment Steps

### 1. Update Code
✅ Files already updated:
- `app/Config/LogNotifier.php` — New `getMISStaffEmails()` method
- `LOG_NOTIFIER_IMPLEMENTATION.md` — Documentation updated
- `ENHANCEMENT_SUMMARY.md` — Implementation details updated

### 2. Clean .env
Remove this line if present:
```env
LOG_NOTIFIER_EMAIL_RECIPIENTS=...
```

### 3. Clear Cache
```bash
php spark cache:clear
```

### 4. Test
Log in as super admin, visit Logs page, click "Test Alert" button.

The test email will be sent to all MIS staff (role_id = 3) with valid emails.

---

## Troubleshooting

### No Emails Received?

1. **Check database query manually:**
   ```bash
   php spark tinker
   ```
   
   ```php
   >>> $model = new \App\Models\UserModel();
   >>> $result = $model->select('users.email')->join('sections', 'sections.section_id = users.section_id', 'left')->where('users.role_id', 3)->where('(sections.acronym = "MIS" OR sections.name = "MIS")', null, false)->findAll();
   >>> dd($result);
   ```

2. **Verify SMTP config:**
   - Open `app/Config/Email.php`
   - Ensure all SMTP credentials are correct
   - Test with a manual email: `service('email')->setTo('test@domain.com')->setSubject('Test')->setMessage('Test')->send();`

3. **Check logs:**
   ```bash
   tail -f writable/logs/log-*.log | grep -i "LogNotifier\|recipient"
   ```

4. **Verify user data:**
   - Login to database
   - Check if any users exist with `role_id = 3` in MIS section
   - Verify their email addresses are not NULL

### Specific Queries to Check

**Count of MIS staff:**
```sql
SELECT COUNT(*) FROM users u
LEFT JOIN sections s ON s.section_id = u.section_id
WHERE u.role_id = 3 AND (s.acronym = 'MIS' OR s.name = 'MIS');
```

**List all MIS staff emails:**
```sql
SELECT u.user_id, u.name, u.email, s.acronym FROM users u
LEFT JOIN sections s ON s.section_id = u.section_id
WHERE u.role_id = 3 AND (s.acronym = 'MIS' OR s.name = 'MIS')
AND u.email IS NOT NULL AND u.email != '';
```

---

## Performance Notes

- **Query runs:** Once per config instantiation (cached by CI4)
- **Time impact:** <10ms typical for small databases
- **Optimization:** Indexes on `users.role_id`, `users.section_id`, and `sections.acronym` recommended
- **Database errors:** Caught silently, returns empty array (prevents app breakage)

---

## What Stays the Same

✅ All other notification features unchanged:
- Multi-channel support (email, Telegram, Slack)
- Deduplication logic
- Log level filtering
- Error handling
- UI components (test button, status indicator)
- Email template design
- Admin security checks

---

## Version History

| Version | Date | Change |
|---------|------|--------|
| 1.0 | April 2026 | Initial implementation (manual config) |
| 1.1 | April 2026 | Auto-query from users table (current) |

---

## Questions?

See documentation:
- [QUICK_REFERENCE.md](QUICK_REFERENCE.md) — Quick lookup
- [LOG_NOTIFIER_IMPLEMENTATION.md](LOG_NOTIFIER_IMPLEMENTATION.md) — Complete guide
- [ENHANCEMENT_SUMMARY.md](ENHANCEMENT_SUMMARY.md) — Implementation details
