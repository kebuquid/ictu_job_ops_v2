<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Update</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f4f6; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

          <!-- Header: green for resolved/closed, blue for in-progress updates -->
          <?php if (in_array($status, ['resolved', 'closed', 'completed'])): ?>
          <tr>
            <td style="background: linear-gradient(135deg, #16a34a, #059669); padding:28px 32px; text-align:center;">
              <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700; letter-spacing:-0.3px;">
                &#10003; Ticket <?= esc(ucfirst($status)) ?>
              </h1>
            </td>
          </tr>
          <?php else: ?>
          <tr>
            <td style="background: linear-gradient(135deg, #2563eb, #4f46e5); padding:28px 32px; text-align:center;">
              <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700; letter-spacing:-0.3px;">
                &#8635; Ticket Status Update
              </h1>
            </td>
          </tr>
          <?php endif; ?>

          <!-- Body -->
          <tr>
            <td style="padding:32px;">
              <p style="margin:0 0 16px; color:#374151; font-size:15px; line-height:1.6;">
                Hi <strong><?= esc($requestorName) ?></strong>,
              </p>

              <?php if (in_array($status, ['resolved', 'closed', 'completed'])): ?>
              <p style="margin:0 0 24px; color:#374151; font-size:15px; line-height:1.6;">
                Great news! Your job ticket has been marked as <strong><?= esc(ucfirst($status)) ?></strong>. If your issue has been fully addressed, no further action is needed. If you believe the issue persists, please feel free to submit a new ticket.
              </p>
              <?php else: ?>
              <p style="margin:0 0 24px; color:#374151; font-size:15px; line-height:1.6;">
                There is a new update on your job ticket. Please review the details below.
              </p>
              <?php endif; ?>

              <!-- Ticket Details Card -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:24px;">
                <tr>
                  <td style="padding:20px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="padding:6px 0; color:#6b7280; font-size:13px; font-weight:600; width:130px; vertical-align:top;">Ticket #</td>
                        <td style="padding:6px 0; color:#111827; font-size:14px; font-weight:700;"><?= esc($ticketId) ?></td>
                      </tr>
                      <tr>
                        <td style="padding:6px 0; color:#6b7280; font-size:13px; font-weight:600; vertical-align:top;">Section</td>
                        <td style="padding:6px 0; color:#111827; font-size:14px;"><?= esc($sectionName) ?></td>
                      </tr>
                      <tr>
                        <td style="padding:6px 0; color:#6b7280; font-size:13px; font-weight:600; vertical-align:top;">Problem</td>
                        <td style="padding:6px 0; color:#111827; font-size:14px; line-height:1.5;"><?= esc($problem) ?></td>
                      </tr>
                      <tr>
                        <td style="padding:6px 0; color:#6b7280; font-size:13px; font-weight:600; vertical-align:top;">Status</td>
                        <td style="padding:6px 0;">
                          <?php
                            $statusColors = [
                              'pending'    => 'background-color:#fef3c7; color:#92400e;',
                              'open'       => 'background-color:#dbeafe; color:#1e40af;',
                              'in_progress'=> 'background-color:#ede9fe; color:#5b21b6;',
                              'resolved'   => 'background-color:#d1fae5; color:#065f46;',
                              'completed'  => 'background-color:#d1fae5; color:#065f46;',
                              'closed'     => 'background-color:#f3f4f6; color:#374151;',
                            ];
                            $style = $statusColors[$status] ?? 'background-color:#f3f4f6; color:#374151;';
                          ?>
                          <span style="display:inline-block; <?= $style ?> font-size:12px; font-weight:700; padding:2px 10px; border-radius:20px; letter-spacing:0.3px;">
                            <?= esc($statusLabel ?? ucfirst(str_replace('_', ' ', $status))) ?>
                          </span>
                        </td>
                      </tr>
                      <?php if (!empty($updateNote)): ?>
                      <tr>
                        <td style="padding:6px 0; color:#6b7280; font-size:13px; font-weight:600; vertical-align:top;">Note</td>
                        <td style="padding:6px 0; color:#374151; font-size:14px; line-height:1.5; font-style:italic;"><?= esc($updateNote) ?></td>
                      </tr>
                      <?php endif; ?>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- CTA Button -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center">
                    <?php if (in_array($status, ['resolved', 'closed', 'completed'])): ?>
                    <a href="<?= esc($ticketUrl) ?>"
                       style="display:inline-block; background:linear-gradient(135deg,#16a34a,#059669); color:#ffffff; text-decoration:none; padding:12px 32px; border-radius:8px; font-size:14px; font-weight:700; letter-spacing:0.2px;">
                      View Ticket Details
                    </a>
                    <?php else: ?>
                    <a href="<?= esc($ticketUrl) ?>"
                       style="display:inline-block; background:linear-gradient(135deg,#2563eb,#4f46e5); color:#ffffff; text-decoration:none; padding:12px 32px; border-radius:8px; font-size:14px; font-weight:700; letter-spacing:0.2px;">
                      View My Ticket
                    </a>
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:20px 32px; border-top:1px solid #e5e7eb; text-align:center;">
              <p style="margin:0; color:#9ca3af; font-size:12px; line-height:1.5;">
                This is an automated notification from the Job-Ops Ticketing System.<br>
                Please do not reply to this email.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
