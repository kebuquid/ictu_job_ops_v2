<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LogViewerController extends BaseController
{
    /**
     * Log file directory
     */
    private string $logDir;

    /**
     * Regex pattern for valid log filename
     */
    private const LOG_FILENAME_PATTERN = '/^log-\d{4}-\d{2}-\d{2}\.log$/';

    public function __construct()
    {
        $this->logDir = WRITEPATH . 'logs' . DIRECTORY_SEPARATOR;
    }

    /**
     * Validate filename matches the expected log format.
     * Prevents path traversal attacks.
     *
     * @param string $filename The filename to validate
     * @return bool True if valid, false otherwise
     */
    private function isValidLogFilename(string $filename): bool
    {
        return preg_match(self::LOG_FILENAME_PATTERN, $filename) === 1;
    }

    /**
     * List all log files with metadata (size, error count).
     *
     * @return ResponseInterface
     */
    public function index()
    {
        // Check authentication via the filter
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $logFiles = [];
        $files = glob($this->logDir . 'log-*.log');

        if ($files === false) {
            $files = [];
        }

        // Sort by modification time, newest first
        usort($files, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        foreach ($files as $filePath) {
            $filename = basename($filePath);
            if (!$this->isValidLogFilename($filename)) {
                continue;
            }

            $fileSize = filesize($filePath) ?: 0;
            $fileSizeKb = round($fileSize / 1024, 2);
            $errorCount = $this->countErrorsInFile($filePath);

            $logFiles[] = [
                'filename' => $filename,
                'display_name' => $this->formatLogFilename($filename),
                'file_size' => $fileSizeKb,
                'error_count' => $errorCount,
                'modified_time' => filemtime($filePath),
            ];
        }

        return view('logs/index', [
            'logFiles' => $logFiles,
            'user' => $user,
        ]);
    }

    /**
     * View a specific log file (parsed into lines).
     *
     * @param string $filename The log filename to view
     * @return ResponseInterface
     */
    public function view(string $filename)
    {
        // Validate filename
        if (!$this->isValidLogFilename($filename)) {
            return $this->response->setStatusCode(400)->setBody(json_encode(['error' => 'Invalid filename']));
        }

        $filePath = $this->logDir . $filename;

        // Ensure file exists
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody(json_encode(['error' => 'Log file not found']));
        }

        $lines = $this->parseLogFile($filePath);

        return $this->response
            ->setContentType('application/json')
            ->setBody(json_encode([
                'filename' => $filename,
                'lines' => $lines,
                'file_size' => round(filesize($filePath) / 1024, 2),
                'modified_time' => filemtime($filePath),
                'modified_time_formatted' => date('F j, Y g:i A', filemtime($filePath)),
            ]));
    }

    /**
     * Download a log file.
     *
     * @param string $filename The log filename to download
     * @return ResponseInterface
     */
    public function download(string $filename)
    {
        // Validate filename
        if (!$this->isValidLogFilename($filename)) {
            return $this->response->setStatusCode(400);
        }

        $filePath = $this->logDir . $filename;

        // Ensure file exists
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404);
        }

        // Stream the file as a download
        return $this->response->download($filePath, null);
    }

    /**
     * Delete a log file (super_admin only).
     *
     * @param string $filename The log filename to delete
     * @return ResponseInterface
     */
    public function delete(string $filename)
    {
        $user = session()->get('user');

        // Check authentication
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        // Check role: only super_admin (role_id = 1) can delete
        if (($user['role_id'] ?? 0) !== 1) {
            return $this->response->setStatusCode(403)->setBody(json_encode(['error' => 'Forbidden: Only super admin can delete logs']));
        }

        // Validate filename
        if (!$this->isValidLogFilename($filename)) {
            return $this->response->setStatusCode(400)->setBody(json_encode(['error' => 'Invalid filename']));
        }

        $filePath = $this->logDir . $filename;

        // Ensure file exists
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody(json_encode(['error' => 'Log file not found']));
        }

        // Attempt to delete
        if (!unlink($filePath)) {
            return $this->response->setStatusCode(500)->setBody(json_encode(['error' => 'Failed to delete file']));
        }

        return $this->response
            ->setContentType('application/json')
            ->setBody(json_encode(['success' => true, 'message' => 'Log file deleted successfully']));
    }

    /**
     * Parse a log file into structured lines.
     * Each line has: timestamp, level, message.
     *
     * @param string $filePath The full path to the log file
     * @return array Array of parsed log lines
     */
    private function parseLogFile(string $filePath): array
    {
        $lines = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [];
        }

        while (($line = fgets($handle)) !== false) {
            $parsed = $this->parseSingleLogLine(trim($line));
            if ($parsed !== null) {
                $lines[] = $parsed;
            }
        }

        fclose($handle);

        return $lines;
    }

    /**
     * Parse a single log line.
     * Expected format: LEVEL - YYYY-MM-DD HH:MM:SS --> message
     * (CodeIgniter 4 format)
     *
     * @param string $line The log line to parse
     * @return array|null Parsed line as ['timestamp', 'level', 'message'], or null if invalid
     */
    private function parseSingleLogLine(string $line): ?array
    {
        if (empty($line)) {
            return null;
        }

        // Pattern: LEVEL - YYYY-MM-DD HH:MM:SS --> message
        // e.g., "DEBUG - 2026-04-07 09:00:01 --> Session: Class initialized..."
        if (preg_match('/^([A-Z]+)\s+-\s+([\d\-\s:]+)\s+-->\s+(.*)$/', $line, $matches)) {
            return [
                'level' => $matches[1],
                'timestamp' => $matches[2],
                'message' => $matches[3],
            ];
        }

        // Fallback: treat entire line as message if no pattern match
        return [
            'timestamp' => '',
            'level' => 'INFO',
            'message' => $line,
        ];
    }

    /**
     * Count ERROR or CRITICAL level entries in a file.
     *
     * @param string $filePath The full path to the log file
     * @return int Count of error lines
     */
    private function countErrorsInFile(string $filePath): int
    {
        $count = 0;
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return 0;
        }

        while (($line = fgets($handle)) !== false) {
            // Check if line starts with ERROR or CRITICAL
            if (preg_match('/^(ERROR|CRITICAL)\s+-/', $line)) {
                $count++;
            }
        }

        fclose($handle);

        return $count;
    }

    /**
     * Format a log filename for display.
     * Converts "log-2026-04-07.log" to "April 7, 2026"
     *
     * @param string $filename The log filename
     * @return string Formatted date string
     */
    private function formatLogFilename(string $filename): string
    {
        // Extract date from filename: log-YYYY-MM-DD.log
        if (preg_match('/log-(\d{4})-(\d{2})-(\d{2})\.log/', $filename, $matches)) {
            $timestamp = mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]);
            return date('F j, Y', $timestamp);
        }

        return $filename;
    }
}
