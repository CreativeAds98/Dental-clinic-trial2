<?php
/**
 * Dr. GR Dental & Aesthetics - Newsletter Subscription Backend
 * Compatible with Hostinger, cPanel, and all standard PHP Apache/Nginx web servers.
 */

// Allow CORS for AJAX requests
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Only POST is accepted.'
    ]);
    exit;
}

// -----------------------------------------------------------------------------
// CONFIGURATION
// -----------------------------------------------------------------------------
// The email where you want to receive new subscriber notifications:
$TO_EMAIL = 'info@drgrdentalaesthetics.com';

// The clinic name shown in the email subject & header:
$CLINIC_NAME = 'Dr. GR Dental & Aesthetics';

// Sender email must match an active mailbox on your Hostinger/cPanel account:
$FROM_EMAIL = 'info@drgrdentalaesthetics.com';

// -----------------------------------------------------------------------------
// SMTP SETTINGS (Configured for Zoho Mail India: mail.zoho.in)
// -----------------------------------------------------------------------------
$USE_SMTP   = true;
$SMTP_HOST  = 'smtppro.zoho.in'; // Zoho Mail India SMTP (smtppro.zoho.in / smtp.zoho.in)
$SMTP_PORT  = 465;               // 465 SSL
$SMTP_USER  = 'info@drgrdentalaesthetics.com';
$SMTP_PASS  = 'Users@759';
// -----------------------------------------------------------------------------

// Read raw JSON input or fallback to $_POST
$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

if (!is_array($data) || empty($data['email'])) {
    $data = $_POST;
}

$email = isset($data['email']) ? trim($data['email']) : '';

// Validation
if (empty($email)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Email address is required.'
    ]);
    exit;
}

// Sanitize & Validate Email
$clean_email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (!filter_var($clean_email, FILTER_VALIDATE_EMAIL) || strlen($clean_email) > 120) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

// Prevent Email Header Injection
if (preg_match("/[\r\n]/", $clean_email)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format detected.'
    ]);
    exit;
}

// Set Indian Standard Timezone (or server local)
date_default_timezone_set('Asia/Kolkata');
$timestamp = date('d M Y, h:i A');
$user_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

// Email Subject
$subject = "🔔 New Newsletter Subscription - " . $CLINIC_NAME;

// Modern HTML Email Template
$body = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>New Newsletter Subscription</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; padding: 30px 10px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;" cellspacing="0" cellpadding="0">
          
          <!-- Header -->
          <tr>
            <td style="background-color: #0f172a; padding: 24px 30px; text-align: left;">
              <h1 style="color: #ffffff; font-size: 20px; margin: 0; font-weight: 700;">' . htmlspecialchars($CLINIC_NAME) . '</h1>
              <p style="color: #ea580c; font-size: 13px; margin: 4px 0 0; font-weight: 500;">Website Notification</p>
            </td>
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding: 30px;">
              <h2 style="color: #1e293b; font-size: 18px; margin: 0 0 12px; font-weight: 600;">New Newsletter Subscriber!</h2>
              <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin: 0 0 20px;">
                A visitor has subscribed to receive clinic updates, tips, and special offers from your website footer.
              </p>

              <!-- Subscriber Box -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fff7ed; border-left: 4px solid #ea580c; border-radius: 6px; padding: 14px 18px; margin-bottom: 24px;">
                <tr>
                  <td>
                    <div style="font-size: 12px; color: #9a3412; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Subscriber Email</div>
                    <div style="font-size: 17px; color: #1e293b; font-weight: 700; margin-top: 4px;">
                      <a href="mailto:' . htmlspecialchars($clean_email) . '" style="color: #ea580c; text-decoration: none;">' . htmlspecialchars($clean_email) . '</a>
                    </div>
                  </td>
                </tr>
              </table>

              <!-- Meta Info -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-top: 1px solid #f1f5f9; padding-top: 16px; font-size: 12px; color: #94a3b8;">
                <tr>
                  <td><strong>Submitted On:</strong> ' . $timestamp . ' (IST)</td>
                </tr>
                <tr>
                  <td style="padding-top: 4px;"><strong>IP Address:</strong> ' . htmlspecialchars($user_ip) . '</td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #f1f5f9; padding: 16px 30px; text-align: center; font-size: 12px; color: #64748b;">
              This notification was automatically sent from the <strong>' . htmlspecialchars($CLINIC_NAME) . '</strong> website.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
';

// Email Headers
$headers   = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=UTF-8';
$headers[] = 'From: ' . $CLINIC_NAME . ' <' . $FROM_EMAIL . '>';
$headers[] = 'Reply-To: ' . $clean_email;
$headers[] = 'X-Mailer: PHP/' . phpversion();

// Send Mail with SMTP first, fallback to PHP mail()
// Send Mail with Zoho SMTP first
$mail_sent = false;
$error_detail = '';

if ($USE_SMTP) {
    $smtp_result = send_smtp_mail([$SMTP_HOST, 'smtp.zoho.in', 'smtppro.zoho.com'], $SMTP_PORT, $SMTP_USER, $SMTP_PASS, $FROM_EMAIL, $CLINIC_NAME, $TO_EMAIL, $subject, $body, $clean_email);
    if ($smtp_result['success']) {
        $mail_sent = true;
    } else {
        $error_detail = $smtp_result['error'];
    }
}

// Fallback to PHP mail() only if SMTP was disabled
if (!$mail_sent && !$USE_SMTP) {
    $mail_sent = @mail($TO_EMAIL, $subject, $body, implode("\r\n", $headers), "-f " . $FROM_EMAIL);
}

if ($mail_sent) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for subscribing! Your email has been registered.'
    ]);
} else {
    http_response_code(500);
    $msg = 'Failed to send email.';
    if (!empty($error_detail)) {
        if (strpos($error_detail, '535') !== false || stripos($error_detail, 'authentication') !== false) {
            $msg = 'Zoho Mail authentication failed. If Two-Factor Authentication (TFA) is enabled on Zoho, please generate and use an App Password from your Zoho Account Security settings.';
        } else {
            $msg = 'Zoho SMTP error: ' . $error_detail;
        }
    }
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}

/**
 * Lightweight pure-PHP SMTP Sender for Zoho Mail
 */
function send_smtp_mail($hosts, $port, $user, $pass, $from, $from_name, $to, $subject, $html_body, $reply_to) {
    if (!is_array($hosts)) {
        $hosts = [$hosts];
    }

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);

    $last_error = 'Could not connect to Zoho mail servers.';

    foreach ($hosts as $host) {
        $protocol = ($port == 465) ? 'ssl://' : '';
        $socket = @stream_socket_client($protocol . $host . ':' . $port, $errno, $errstr, 8, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            $last_error = "Connection to $host failed: $errstr ($errno)";
            continue;
        }

        $read = function() use ($socket) {
            $response = '';
            while ($line = fgets($socket, 515)) {
                $response .= $line;
                if (substr($line, 3, 1) == ' ') break;
            }
            return $response;
        };

        $send = function($cmd) use ($socket, $read) {
            fputs($socket, $cmd . "\r\n");
            return $read();
        };

        $read();
        $send("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $send("AUTH LOGIN");
        $send(base64_encode($user));
        $auth_res = $send(base64_encode($pass));
        
        if (strpos($auth_res, '235') === false) {
            $last_error = "Zoho Auth failed: " . trim($auth_res);
            fclose($socket);
            continue;
        }

        $send("MAIL FROM:<$from>");
        $send("RCPT TO:<$to>");
        $send("DATA");

        $normalized_body = preg_replace("/(?<!\r)\n/", "\r\n", $html_body);

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $from_name <$from>\r\n";
        $headers .= "To: <$to>\r\n";
        $headers .= "Reply-To: <$reply_to>\r\n";
        $headers .= "Subject: $subject\r\n";

        $data_res = $send($headers . "\r\n" . $normalized_body . "\r\n.");
        $send("QUIT");
        fclose($socket);

        if (strpos($data_res, '250') !== false) {
            return ['success' => true, 'error' => ''];
        } else {
            $last_error = "Data send failed: " . trim($data_res);
        }
    }

    return ['success' => false, 'error' => $last_error];
}
