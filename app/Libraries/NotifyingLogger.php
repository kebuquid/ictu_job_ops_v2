<?php

namespace App\Libraries;

use CodeIgniter\Log\Logger;

/**
 * NotifyingLogger: Extends CI4's Logger to trigger notifications
 *
 * Logs to file first (always), then checks if the level warrants a notification.
 * Notifications are sent asynchronously via LogNotifier.
 */
class NotifyingLogger extends Logger
{
    private ?LogNotifier $notifier = null;

    /**
     * Constructor accepts Logger config object (same as parent Logger class)
     * 
     * @param mixed $config Logger configuration object/array
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        // Lazy-load notifier on first use to avoid circular dependencies
    }

    /**
     * Get or create the LogNotifier instance (lazy-loaded)
     */
    private function getNotifier(): LogNotifier
    {
        if ($this->notifier === null) {
            $this->notifier = new LogNotifier();
        }
        return $this->notifier;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level = 'info', $message = '', array $context = []): void
    {
        // Always log to file first (parent implementation)
        parent::log($level, $message, $context);

        // Then check if we should notify about this level
        try {
            // Normalize level name
            $levelName = is_int($level) ? $this->levelMap[$level] ?? strtoupper($level) : strtoupper($level);

            // Extract context information
            $extraContext = [];
            if (isset($context['file'])) {
                $extraContext['file'] = $context['file'];
            }
            if (isset($context['line'])) {
                $extraContext['line'] = $context['line'];
            }

            // Send notification (will check config and deduplication internally)
            $this->getNotifier()->notify($levelName, (string) $message, $extraContext);
        } catch (\Throwable $e) {
            // Silently fail — broken notifier must never break logging
            // Optionally log the error at a safe level
            parent::log('debug', '[NotifyingLogger] Failed to notify: ' . $e->getMessage());
        }
    }
}
