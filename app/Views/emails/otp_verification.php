<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f4f6; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

          <!-- Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #d97706, #b45309); padding:28px 32px; text-align:center;">
              <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700; letter-spacing:-0.3px;">
                &#128274; Account Recovery Email Verification
              </h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:32px;">
              <p style="margin:0 0 16px; color:#374151; font-size:15px; line-height:1.6;">
                Hi <strong><?= esc($userName) ?></strong>,
              </p>
              <p style="margin:0 0 24px; color:#374151; font-size:15px; line-height:1.6;">
                We received a request to link this address as your account recovery email. Use the one-time verification code below to confirm. Do not share this code with anyone.
              </p>

              <!-- OTP Display Card -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fffbeb; border:1px solid #fde68a; border-radius:8px; margin-bottom:24px;">
                <tr>
                  <td style="padding:28px; text-align:center;">
                    <p style="margin:0 0 8px; color:#92400e; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Your Verification Code</p>
                    <p style="margin:0; color:#162557; font-size:40px; font-weight:800; letter-spacing:12px; line-height:1.2;"><?= esc($otp) ?></p>
                    <p style="margin:12px 0 0; color:#b45309; font-size:12px;">
                      This code expires in <strong><?= esc($expiryMinutes ?? 10) ?> minutes</strong>.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Warning note -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fef2f2; border:1px solid #fecaca; border-radius:8px; margin-bottom:24px;">
                <tr>
                  <td style="padding:14px 18px;">
                    <p style="margin:0; color:#991b1b; font-size:13px; line-height:1.5;">
                      <strong>Did not request this?</strong><br>
                      If you did not initiate this request, you can safely ignore this email. Your account remains secure and no changes have been made.
                    </p>
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
