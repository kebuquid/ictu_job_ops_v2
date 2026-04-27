<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class LogNotifier extends BaseConfig
{
    /**
     * Enable or disable the notification system
     */
    public bool $enabled = true;

    /**
     * Active notification channels: 'email', 'telegram', 'slack'
     * @var array<string>
     */
    public array $channels = ['email'];

    /**
     * Email recipients for log alerts
     * Lazy-loaded from database on first access (not in constructor)
     * @var array<string>|null
     */
    public ?array $emailRecipients = null;

    /**
     * Email from address
     */
    public string $emailFromAddress = '';

    /**
     * Email from name
     */
    public string $emailFromName = '';

    /**
     * Flag to track if email config has been loaded
     */
    private bool $emailConfigLoaded = false;

    /**
     * Telegram Bot Token
     */
    public string $telegramBotToken = '';

    /**
     * Telegram Chat ID
     */
    public string $telegramChatId = '';

    /**
     * Slack Webhook URL
     */
    public string $slackWebhookUrl = '';

    /**
     * Deduplication TTL in seconds — prevents duplicate notifications
     * within this time window for the same error signature
     */
    public int $deduplicationTtl = 300;

    /**
     * Log levels that trigger notifications
     * @var array<string>
     */
    public array $notifyOnLevels = ['error', 'critical', 'emergency', 'alert'];

    /**
     * Application name (displayed in notifications)
     */
    public string $appName = 'MyApp';

    /**
     * Base URL to the log viewer
     * e.g., 'https://yourdomain.com/logs/view/'
     */
    public string $logViewerBaseUrl = '';

    public function __construct()
    {
        parent::__construct();

        // Load from .env if available
        $this->enabled = (bool) env('LOG_NOTIFIER_ENABLED', true);

        $channels = env('LOG_NOTIFIER_CHANNELS', 'email');
        $this->channels = array_filter(array_map(
            'trim',
            explode(',', $channels)
        ));

        // Email recipients are lazy-loaded on first access (prevents database queries during init)
        // Email config is also lazy-loaded to avoid config chain issues

        // Set email from address/name from .env, will fallback to Email config on first access
        $this->emailFromAddress = env('LOG_NOTIFIER_EMAIL_FROM', '');
        $this->emailFromName = env('LOG_NOTIFIER_EMAIL_FROM_NAME', '');

        $this->telegramBotToken = env('LOG_NOTIFIER_TELEGRAM_BOT_TOKEN', '');
        $this->telegramChatId = env('LOG_NOTIFIER_TELEGRAM_CHAT_ID', '');
        $this->slackWebhookUrl = env('LOG_NOTIFIER_SLACK_WEBHOOK_URL', '');

        $this->deduplicationTtl = (int) env('LOG_NOTIFIER_DEDUP_TTL', 300);
        $this->appName = env('APP_NAME', 'ICTU Job Operations');
        $this->logViewerBaseUrl = env('LOG_NOTIFIER_BASE_URL', base_url('logs/view/'));
    }

    /**
     * Get email from address (lazy-loads from Email config if not set in .env)
     */
    public function getEmailFromAddress(): string
    {
        // If already set from .env, return it
        if (!empty($this->emailFromAddress)) {
            return $this->emailFromAddress;
        }

        // Otherwise, fallback to Email config
        if (!$this->emailConfigLoaded) {
            try {
                $emailConfig = config('Email');
                $this->emailFromAddress = $emailConfig->fromEmail ?? '';
                $this->emailConfigLoaded = true;
            } catch (\Exception $e) {
                log_message('error', '[LogNotifier] Failed to load Email config: ' . $e->getMessage());
            }
        }

        return $this->emailFromAddress;
    }

    /**
     * Get email from name (lazy-loads from Email config if not set in .env)
     */
    public function getEmailFromName(): string
    {
        // If already set from .env, return it
        if (!empty($this->emailFromName)) {
            return $this->emailFromName;
        }

        // Otherwise, fallback to Email config
        if (!$this->emailConfigLoaded) {
            try {
                $emailConfig = config('Email');
                $this->emailFromName = $emailConfig->fromName ?? 'ICTU Job Operations';
                $this->emailConfigLoaded = true;
            } catch (\Exception $e) {
                log_message('error', '[LogNotifier] Failed to load Email config: ' . $e->getMessage());
            }
        }

        return $this->emailFromName;
    }

    /**
     * Get email recipients (lazy-loaded from database)
     * Called only when needed, not during config initialization
     *
     * @return array<string> Array of email addresses
     */
    public function getEmailRecipients(): array
    {
        if ($this->emailRecipients === null) {
            $this->emailRecipients = $this->getMISStaffEmails();
        }
        return $this->emailRecipients;
    }

    /**
     * Query email recipients from users table
     * Returns all active users with role_id = 3 (ICTU Staff) in the MIS section
     *
     * @return array<string> Array of email addresses
     */
    private function getMISStaffEmails(): array
    {
        try {
            $userModel = new \App\Models\UserModel();

            // Query users with role_id = 3 (ICTU staff) joined with sections table
            // Filter by MIS section
            $users = $userModel
                    ->select('users.email')
                    ->join('sections', 'sections.section_id = users.section_id', 'left')
                    ->groupStart()
                        ->where('users.role_id', 2)
                        ->orWhere('users.role_id', 3)
                    ->groupEnd()
                    ->whereIn('sections.acronym', ['MIS'])
                    ->findAll();

            log_message('debug', '[LogNotifier::getMISStaffEmails] Found ' . count($users) . ' users');

            // Extract email addresses and filter out empty ones
            $emails = array_column($users, 'email');
            $validEmails = array_filter(array_map('trim', $emails), function ($email) {
                return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
            });
            
            log_message('debug', '[LogNotifier::getMISStaffEmails] Valid emails: ' . count($validEmails));
            
            return $validEmails;
        } catch (\Exception $e) {
            // Log error but don't break the app
            log_message('error', '[LogNotifier::getMISStaffEmails] Failed to query MIS staff emails: ' . $e->getMessage());
            return [];
        }
    }
}
