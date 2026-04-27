<?php
/**
 * Log Alert Email Template
 *
 * Expected variables:
 *  - $level        : string — log level (ERROR, CRITICAL, EMERGENCY, ALERT)
 *  - $message      : string — full log message
 *  - $timestamp    : string — formatted date/time
 *  - $environment  : string — app environment (production, development, etc.)
 *  - $appName      : string — application name
 *  - $logViewerUrl : string — URL to log viewer (optional)
 *  - $triggeredBy  : string|null — PHP file:line that triggered the log
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($appName) ?> — <?= esc($level) ?> Alert</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px;">
                    
                    <!-- Header: Color-coded by severity -->
                    <?php
                        $levelLower = strtolower($level);
                        $colors = [
                            'error' => '#A32D2D',
                            'critical' => '#7F77DD',
                            'emergency' => '#501313',
                            'alert' => '#854F0B',
                        ];
                        $headerColor = $colors[$levelLower] ?? '#999999';
                        $icons = [
                            'error' => '!',
                            'critical' => '✕',
                            'emergency' => '!',
                            'alert' => '🔔',
                        ];
                        $icon = $icons[$levelLower] ?? '⚠';
                    ?>
                    <tr>
                        <td style="background-color: <?= esc($headerColor) ?>; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <div style="font-size: 48px; color: white; margin-bottom: 12px; font-weight: bold;">
                                <?= $icon === '🔔' ? '🔔' : ($icon === '✕' ? '✕' : '!') ?>
                            </div>
                            <h1 style="margin: 0; color: white; font-size: 28px; font-weight: bold; letter-spacing: -0.5px;">
                                <?= esc(strtoupper($level)) ?>
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: normal;">
                                <?= esc($appName) ?>
                            </p>
                        </td>
                    </tr>

                    <!-- Body: White background -->
                    <tr>
                        <td style="background-color: white; padding: 32px;">
                            
                            <!-- Intro -->
                            <p style="margin: 0 0 24px; color: #374151; font-size: 16px; line-height: 1.6;">
                                An issue was detected in your application.
                            </p>

                            <!-- Details Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border-left: 4px solid <?= esc($headerColor) ?>; border-radius: 4px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        
                                        <!-- Timestamp -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 16px;">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 12px; font-weight: 600; width: 100px; vertical-align: top;">Timestamp</td>
                                                <td style="color: #111827; font-size: 14px; font-family: 'Courier New', monospace;">
                                                    <?= esc($timestamp) ?>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Environment Badge -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 16px;">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 12px; font-weight: 600; width: 100px; vertical-align: top;">Environment</td>
                                                <td style="color: #111827; font-size: 14px;">
                                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: <?= strtolower($environment) === 'production' ? '#fee2e2' : '#d1fae5' ?>; color: <?= strtolower($environment) === 'production' ? '#991b1b' : '#065f46' ?>;">
                                                        <?= esc(ucfirst($environment)) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Level -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 16px;">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 12px; font-weight: 600; width: 100px; vertical-align: top;">Level</td>
                                                <td style="color: #111827; font-size: 14px; font-weight: 600;">
                                                    <?= esc(strtoupper($level)) ?>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Message -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 16px;">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 12px; font-weight: 600; vertical-align: top;">Message</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #374151; font-size: 13px; font-family: 'Courier New', monospace; white-space: pre-wrap; word-break: break-word; padding-top: 8px;">
                                                    <?= nl2br(esc($message)) ?>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Triggered By (if available) -->
                                        <?php if ($triggeredBy): ?>
                                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td style="color: #6b7280; font-size: 12px; font-weight: 600; vertical-align: top;">Triggered By</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #6366f1; font-size: 13px; font-family: 'Courier New', monospace; padding-top: 6px;">
                                                        <?= esc($triggeredBy) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            </table>

                            <!-- Call-to-Action Button (if log viewer URL is provided) -->
                            <?php if (!empty($logViewerUrl)): ?>
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 28px;">
                                    <tr>
                                        <td align="center">
                                            <!--[if mso]>
                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
                                                    href="<?= esc($logViewerUrl) ?>" style="height: 44px; v-text-anchor: middle; width: 200px;"
                                                    arcsize="8%" stroke="f" fillcolor="<?= esc($headerColor) ?>">
                                                    <w:anchorlock/>
                                                    <center style="color: white; font-family: Arial, sans-serif; font-size: 14px; font-weight: 600;">
                                                        View Log File
                                                    </center>
                                                </v:roundrect>
                                            <![endif]-->
                                            <a href="<?= esc($logViewerUrl) ?>" style="display: inline-block; padding: 12px 28px; background-color: <?= esc($headerColor) ?>; color: white; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 4px; mso-padding-alt: 12px 28px; text-align: center;">
                                                View Log File
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            <?php endif; ?>

                        </td>
                    </tr>

                    <!-- Footer: Light gray background -->
                    <tr>
                        <td style="background-color: #f3f4f6; padding: 24px; text-align: center; border-radius: 0 0 8px 8px;">
                            <p style="margin: 0 0 12px; color: #6b7280; font-size: 13px; line-height: 1.5;">
                                This alert was sent by <strong><?= esc($appName) ?></strong> automated monitoring.
                            </p>
                            <p style="margin: 0 0 12px; color: #6b7280; font-size: 13px; line-height: 1.5;">
                                You are receiving this because your email is registered as a developer contact.
                            </p>
                            <p style="margin: 0 0 12px; color: #6b7280; font-size: 13px; line-height: 1.5;">
                                To stop receiving alerts, contact your system administrator.
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px; font-style: italic;">
                                Do not reply to this email.
                            </p>
                            <p style="margin: 12px 0 0; color: #d1d5db; font-size: 11px;">
                                Sent on <?= date('F j, Y \a\t g:i A') ?>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
