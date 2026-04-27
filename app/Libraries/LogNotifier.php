<?php

namespace App\Libraries;

use Config\LogNotifier as LogNotifierConfig;
use CodeIgniter\HTTP\CURLRequest;

/**
 * LogNotifier: Multi-channel notification system for log alerts
 *
 * Sends notifications (email, Telegram, Slack) for critical log levels.
 * Implements deduplication using CI4 cache to prevent notification spam.
 */
class LogNotifier
{
    private LogNotifierConfig $config;
    private CURLRequest $curl;

    public function __construct()
    {
        $this->config = config('LogNotifier');
        $this->curl = service('curlrequest');
    }

    /**
     * Send notification for a log entry
     *
     * @param string $level    Log level (ERROR, CRITICAL, etc.)
     * @param string $message  Full log message
     * @param array  $context  Additional context (file, line, etc.)
     * @return array Results per channel: ['email' => true/false, 'telegram' => ...] or empty
     */
    public function notify(string $level, string $message, array $context = []): array
    {
        if (!$this->config->enabled) {
            return [];
        }

        // Check if level is in notification list
        $normalizedLevel = strtolower($level);
        if (!in_array($normalizedLevel, array_map('strtolower', $this->config->notifyOnLevels), true)) {
            return [];
        }

        // Check deduplication
        if ($this->isDuplicate($level, $message)) {
            return [];
        }

        $results = [];

        try {
            // Mark as notified to prevent duplicates
            $this->markNotified($level, $message);

            // Send via each enabled channel
            foreach ($this->config->channels as $channel) {
                $method = 'send' . ucfirst(strtolower($channel));
                if (method_exists($this, $method)) {
                    try {
                        $results[$channel] = $this->$method($level, $message, $context);
                    } catch (\Throwable $e) {
                        log_message('error', '[LogNotifier] Failed to send via ' . $channel . ': ' . $e->getMessage());
                        $results[$channel] = false;
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('error', '[LogNotifier] Unexpected error: ' . $e->getMessage());
        }

        return $results;
    }

    /**
     * Send email notification
     *
     * @param string $level    Log level
     * @param string $message  Log message
     * @param array  $context  Additional context
     * @return bool Success status
     */
    private function sendEmail(string $level, string $message, array $context = []): bool
    {
        $recipients = $this->config->getEmailRecipients();
        
        if (empty($recipients)) {
            log_message('warning', '[LogNotifier::sendEmail] No email recipients configured. Count: ' . count($recipients));
            return false;
        }

        try {
            $email = service('email');

            // Extract first line of message for subject
            $lines = explode("\n", $message);
            $firstLine = $lines[0] ?? '';
            $firstLine = strlen($firstLine) > 60 ? substr($firstLine, 0, 60) . '...' : $firstLine;

            // Get formatted timestamp
            $timestamp = date('F j, Y g:i A');

            // Prepare variables for the email template
            $emailViewData = [
                'level' => strtoupper($level),
                'message' => $message,
                'timestamp' => $timestamp,
                'environment' => ENVIRONMENT,
                'appName' => $this->config->appName,
                'logViewerUrl' => '',
                'triggeredBy' => (($context['file'] ?? null) && ($context['line'] ?? null)) ? 
                    $context['file'] . ':' . $context['line'] : 
                    null,
            ];

            // Render the HTML email body
            $emailBody = view('emails/log_alert', $emailViewData);
            $plainTextBody = strip_tags($emailBody);

            // Set email details
            $email->setFrom($this->config->getEmailFromAddress(), $this->config->getEmailFromName());
            $email->setTo(implode(', ', $recipients));
            $email->setSubject("[{$this->config->appName}] {$level} — {$firstLine}");
            $email->setMessage($emailBody);
            $email->setAltMessage($plainTextBody);

            return $email->send();
        } catch (\Throwable $e) {
            log_message('error', '[LogNotifier Email] Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Telegram notification
     *
     * @param string $level    Log level
     * @param string $message  Log message
     * @param array  $context  Additional context
     * @return bool Success status
     */
    private function sendTelegram(string $level, string $message, array $context = []): bool
    {
        if (empty($this->config->telegramBotToken) || empty($this->config->telegramChatId)) {
            return false;
        }

        try {
            // Truncate message if too long for Telegram
            $messagePreview = strlen($message) > 1000 ? 
                substr($message, 0, 1000) . '...' : 
                $message;

            $text = "*{$this->config->appName} — {$level}*\n\n" .
                    $messagePreview . "\n\n" .
                    "_" . date('Y-m-d H:i:s') . "_";

            $url = "https://api.telegram.org/bot{$this->config->telegramBotToken}/sendMessage";

            $response = $this->curl->request('POST', $url, [
                'form_params' => [
                    'chat_id' => $this->config->telegramChatId,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                ],
                'timeout' => 5,
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            log_message('error', '[LogNotifier Telegram] Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Slack notification
     *
     * @param string $level    Log level
     * @param string $message  Log message
     * @param array  $context  Additional context
     * @return bool Success status
     */
    private function sendSlack(string $level, string $message, array $context = []): bool
    {
        if (empty($this->config->slackWebhookUrl)) {
            return false;
        }

        try {
            // Determine color based on level
            $colors = [
                'ERROR' => '#A32D2D',
                'CRITICAL' => '#7F77DD',
                'EMERGENCY' => '#501313',
                'ALERT' => '#854F0B',
            ];

            $color = $colors[strtoupper($level)] ?? '#999999';

            // Truncate if needed
            $text = strlen($message) > 500 ? 
                substr($message, 0, 500) . '...' : 
                $message;

            $payload = [
                'attachments' => [
                    [
                        'color' => $color,
                        'title' => "{$this->config->appName} — {$level}",
                        'text' => $text,
                        'ts' => time(),
                        'fields' => [
                            [
                                'title' => 'Timestamp',
                                'value' => date('Y-m-d H:i:s'),
                                'short' => true,
                            ],
                            [
                                'title' => 'Environment',
                                'value' => ENVIRONMENT,
                                'short' => true,
                            ],
                        ],
                    ],
                ],
            ];

            $response = $this->curl->request('POST', $this->config->slackWebhookUrl, [
                'json' => $payload,
                'timeout' => 5,
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            log_message('error', '[LogNotifier Slack] Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if this notification is a duplicate (within TTL window)
     *
     * @param string $level   Log level
     * @param string $message Log message
     * @return bool True if duplicate, false if new
     */
    private function isDuplicate(string $level, string $message): bool
    {
        $cache = cache();
        $key = $this->getDuplicateKey($level, $message);
        return $cache->get($key) !== null;
    }

    /**
     * Mark notification as sent (for deduplication)
     *
     * @param string $level   Log level
     * @param string $message Log message
     */
    private function markNotified(string $level, string $message): void
    {
        $cache = cache();
        $key = $this->getDuplicateKey($level, $message);
        $cache->save($key, true, $this->config->deduplicationTtl);
    }

    /**
     * Generate a deduplication cache key
     *
     * @param string $level   Log level
     * @param string $message Log message
     * @return string Cache key
     */
    private function getDuplicateKey(string $level, string $message): string
    {
        // Use first line of message to create a signature
        $lines = explode("\n", $message);
        $signature = md5($level . '|' . $lines[0]);
        return "log_notify_{$signature}";
    }
}
