<?php
/**
 * Dr. GR Dental & Aesthetics - Appointment Booking Backend
 * Sends formatted appointment notification emails to info@drgrdentalaesthetics.com via Zoho SMTP
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
$TO_EMAIL    = 'info@drgrdentalaesthetics.com';
$CLINIC_NAME = 'Dr. GR Dental & Aesthetics';
$FROM_EMAIL  = 'info@drgrdentalaesthetics.com';

// Zoho Mail India SMTP Settings (Verified)
$USE_SMTP    = true;
$SMTP_HOST   = 'smtppro.zoho.in';
$SMTP_PORT   = 465;
$SMTP_USER   = 'info@drgrdentalaesthetics.com';
$SMTP_PASS   = 'Users@759';
// -----------------------------------------------------------------------------

// Read raw JSON input or fallback to $_POST
$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

if (!is_array($data)) {
    $data = $_POST;
}

// Extract fields
$name    = isset($data['name']) ? trim($data['name']) : '';
$phone   = isset($data['phone']) ? trim($data['phone']) : '';
$email   = isset($data['email']) ? trim($data['email']) : '';
$date    = isset($data['date']) ? trim($data['date']) : '';
$time    = isset($data['time']) ? trim($data['time']) : '';
$service = isset($data['service']) ? trim($data['service']) : (isset($data['treatment']) ? trim($data['treatment']) : 'General Consultation');
$notes   = isset($data['message']) ? trim($data['message']) : (isset($data['notes']) ? trim($data['notes']) : '');

// Validation
if (empty($name) || strlen($name) < 2) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter the patient full name.']);
    exit;
}

if (empty($phone) || !preg_match('/^[0-9+ ]{8,15}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid mobile number.']);
    exit;
}

// Clean phone for WhatsApp / Tel links
$clean_phone_digits = preg_replace('/[^0-9]/', '', $phone);
if (strlen($clean_phone_digits) === 10) {
    $wa_phone = '91' . $clean_phone_digits;
} else {
    $wa_phone = $clean_phone_digits;
}

// Determine if this is a General Enquiry or an Appointment Booking
$type = isset($data['type']) ? trim($data['type']) : '';
$is_enquiry = ($type === 'general_enquiry' || (empty($date) && !empty($notes)));

// Set Indian Standard Time
date_default_timezone_set('Asia/Kolkata');
$timestamp = date('d M Y, h:i A');
$formatted_date = !empty($date) ? date('D, d M Y', strtotime($date)) : 'Not specified';
$user_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

if ($is_enquiry) {
    // General Enquiry Configuration
    $subject        = "📩 New General Enquiry: " . htmlspecialchars($name) . " - " . htmlspecialchars($service);
    $category_badge = "GENERAL ENQUIRY";
    $badge_color    = "#0284c7"; // Cyan / Blue for Enquiry
    $intro_banner   = "A visitor has sent a General Enquiry from your website Contact form.";
    $wa_message     = urlencode("Hello $name, this is Dr. GR Dental & Aesthetics, Coimbatore. We received your enquiry regarding $service. We would be happy to assist you.");
} else {
    // Appointment Booking Configuration
    $subject        = "📅 New Appointment Request: " . htmlspecialchars($name) . " - " . htmlspecialchars($service);
    $category_badge = "NEW PATIENT APPOINTMENT BOOKING";
    $badge_color    = "#f26b2a"; // Orange for Booking
    $intro_banner   = "A new patient has submitted an appointment booking online.";
    $wa_message     = urlencode("Hello $name, this is Dr. GR Dental & Aesthetics, Coimbatore. We received your appointment request for $formatted_date ($time) regarding $service. We would like to confirm your booking.");
}

// Modern HTML Email Template
$body = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>' . htmlspecialchars($category_badge) . '</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 30px 10px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;" cellspacing="0" cellpadding="0">
          
          <!-- Header -->
          <tr>
            <td style="background-color: #0f172a; padding: 26px 32px; text-align: left; border-bottom: 3px solid ' . $badge_color . ';">
              <h1 style="color: #ffffff; font-size: 20px; margin: 0; font-weight: 700;">' . htmlspecialchars($CLINIC_NAME) . '</h1>
              <p style="color: ' . $badge_color . '; font-size: 13px; margin: 4px 0 0; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">' . htmlspecialchars($category_badge) . '</p>
            </td>
          </tr>

          <!-- Main Content -->
          <tr>
            <td style="padding: 30px 32px;">
              <div style="background-color: #ecfdf5; border-left: 4px solid #10b981; border-radius: 6px; padding: 12px 16px; margin-bottom: 24px;">
                <span style="color: #065f46; font-weight: 600; font-size: 14px;">' . htmlspecialchars($intro_banner) . '</span>
              </div>

              <!-- Details Card -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <tr>
                  <td style="padding: 18px 20px;">
                    
                    <!-- Sender Name -->
                    <div style="margin-bottom: 14px;">
                      <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">Name</div>
                      <div style="font-size: 18px; font-weight: 800; color: #0f172a; margin-top: 2px;">' . htmlspecialchars($name) . '</div>
                    </div>

                    <!-- Phone & WhatsApp -->
                    <div style="margin-bottom: 14px;">
                      <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">Phone Number</div>
                      <div style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                        <a href="tel:' . htmlspecialchars($clean_phone_digits) . '" style="color: #f26b2a; text-decoration: none;">📞 ' . htmlspecialchars($phone) . '</a>
                      </div>
                    </div>

                    <!-- Email -->
                    ' . (!empty($email) ? '
                    <div style="margin-bottom: 14px;">
                      <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">Email Address</div>
                      <div style="font-size: 14px; font-weight: 600; color: #334155; margin-top: 2px;">
                        <a href="mailto:' . htmlspecialchars($email) . '" style="color: #0284c7; text-decoration: none;">✉️ ' . htmlspecialchars($email) . '</a>
                      </div>
                    </div>' : '') . '

                    <!-- Preferred Slot (Only for Appointments) -->
                    ' . (!$is_enquiry ? '
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 14px;">
                      <tr>
                        <td width="50%" valign="top">
                          <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">Preferred Date</div>
                          <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 2px;">🗓️ ' . htmlspecialchars($formatted_date) . '</div>
                        </td>
                        <td width="50%" valign="top">
                          <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">Preferred Time</div>
                          <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 2px;">⏰ ' . htmlspecialchars($time ?: 'Flexible') . '</div>
                        </td>
                      </tr>
                    </table>' : '') . '

                    <!-- Treatment / Concern -->
                    <div style="margin-bottom: 14px;">
                      <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">' . ($is_enquiry ? 'Treatment / Enquiry Topic' : 'Treatment / Speciality') . '</div>
                      <div style="font-size: 15px; font-weight: 700; color: #f26b2a; margin-top: 2px;">🩺 ' . htmlspecialchars($service) . '</div>
                    </div>

                    <!-- Message / Notes -->
                    ' . (!empty($notes) ? '
                    <div>
                      <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">' . ($is_enquiry ? 'Message / Enquiry Details' : 'Patient Notes / Query') . '</div>
                      <div style="font-size: 14px; color: #334155; margin-top: 4px; background: #ffffff; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0; line-height: 1.6;">' . nl2br(htmlspecialchars($notes)) . '</div>
                    </div>' : '') . '

                  </td>
                </tr>
              </table>

              <!-- Action Buttons for Clinic Staff -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                <tr>
                  <td align="center">
                    <a href="https://wa.me/' . $wa_phone . '?text=' . $wa_message . '" target="_blank" style="display: inline-block; background-color: #25D366; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 14px; margin-right: 8px;">
                      💬 Quick Confirm on WhatsApp
                    </a>
                    <a href="tel:' . htmlspecialchars($clean_phone_digits) . '" style="display: inline-block; background-color: #0f172a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 14px;">
                      📞 Call Patient
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Meta Footer -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-top: 1px solid #f1f5f9; padding-top: 14px; font-size: 12px; color: #94a3b8;">
                <tr>
                  <td>Submitted On: <strong>' . $timestamp . ' (IST)</strong> | IP: ' . htmlspecialchars($user_ip) . '</td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #f8fafc; padding: 16px 32px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9;">
              This notification was generated automatically by the <strong>' . htmlspecialchars($CLINIC_NAME) . '</strong> website booking system.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
';

// Send Mail with Zoho SMTP
$smtp_result = send_smtp_mail([$SMTP_HOST, 'smtp.zoho.in', 'smtppro.zoho.com'], $SMTP_PORT, $SMTP_USER, $SMTP_PASS, $FROM_EMAIL, $CLINIC_NAME, $TO_EMAIL, $subject, $body, (!empty($email) ? $email : $FROM_EMAIL));

if ($smtp_result['success']) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Appointment request submitted successfully!'
    ]);
} else {
    // Fallback to PHP mail()
    $headers   = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $CLINIC_NAME . ' <' . $FROM_EMAIL . '>';
    if (!empty($email)) {
        $headers[] = 'Reply-To: ' . $email;
    }
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    $mail_sent = @mail($TO_EMAIL, $subject, $body, implode("\r\n", $headers), "-f " . $FROM_EMAIL);

    if ($mail_sent) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Appointment request submitted successfully!'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send appointment request. ' . $smtp_result['error']
        ]);
    }
}

/**
 * Pure PHP Zoho SMTP Sender
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
