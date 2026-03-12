<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Submitted</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f4f6; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

          <!-- Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #059669, #0d9488); padding:28px 32px; text-align:center;">
              <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700; letter-spacing:-0.3px;">
                &#10003; Ticket Submitted Successfully
              </h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:32px;">
              <p style="margin:0 0 16px; color:#374151; font-size:15px; line-height:1.6;">
                Hi <strong><?= esc($requestorName) ?></strong>,
              </p>
              <p style="margin:0 0 24px; color:#374151; font-size:15px; line-height:1.6;">
                Your job ticket has been successfully submitted and is now in the queue. Our team will review it and assign a technician shortly. You will receive another notification once it is assigned or updated.
              </p>

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
                          <span style="display:inline-block; background-color:#fef3c7; color:#92400e; font-size:12px; font-weight:700; padding:2px 10px; border-radius:20px; letter-spacing:0.3px;">Pending</span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- CTA Button -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center">
                    <a href="<?= esc($ticketUrl) ?>"
                       style="display:inline-block; background:linear-gradient(135deg,#059669,#0d9488); color:#ffffff; text-decoration:none; padding:12px 32px; border-radius:8px; font-size:14px; font-weight:700; letter-spacing:0.2px;">
                      Track My Ticket
                    </a>
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
