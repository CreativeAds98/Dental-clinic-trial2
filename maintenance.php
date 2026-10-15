<?php
/**
 * Dr. GR Dental & Aesthetics - Website Under Maintenance Page
 * SEO-safe HTTP 503 response with emergency clinic contacts and countdown
 */

// -----------------------------------------------------------------------------
// CONFIGURATION
// -----------------------------------------------------------------------------
// Estimated time back online (e.g. '2026-10-15 18:00:00' or any strtotime format)
$MAINTENANCE_UNTIL   = '2026-10-15 18:00:00';
$RETRY_AFTER_SECONDS = 7200; // 2 hours for search crawlers

// Clinic Contact Info
$CLINIC_NAME       = 'Dr. GR Dental & Aesthetics';
$CLINIC_PHONE_1    = '+91 72009 20929';
$CLINIC_PHONE_2    = '+91 89400 44644';
$WHATSAPP_NUMBER   = '917200920929';
$CLINIC_EMAIL      = 'info@drgrdentalaesthetics.com';
$CLINIC_LOCATION   = 'Coimbatore, Tamil Nadu';

// Optional: Bypass maintenance for specific admin/developer IPs
$ALLOWED_IPS = ['127.0.0.1', '::1'];
if (!empty($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $ALLOWED_IPS, true)) {
    // If you want developers to bypass:
    // header('Location: index.html'); exit;
}

// -----------------------------------------------------------------------------
// SEO 503 HEADERS (Prevents search engines from penalizing website rankings)
// -----------------------------------------------------------------------------
http_response_code(503);
header('Retry-After: ' . $RETRY_AFTER_SECONDS);
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Under Maintenance | <?php echo htmlspecialchars($CLINIC_NAME); ?></title>
  <meta name="description" content="We are performing scheduled maintenance to enhance your dental care experience. For urgent appointments, call us directly.">
  <meta name="robots" content="noindex, nofollow">
  
  <link rel="icon" type="image/png" href="favicon.png">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    :root {
      --primary-dark: #070c18;
      --card-bg: rgba(15, 23, 42, 0.78);
      --card-border: rgba(255, 255, 255, 0.08);
      --accent-orange: #f26b2a;
      --accent-orange-hover: #e05816;
      --accent-cyan: #38bdf8;
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: radial-gradient(circle at 50% 15%, #1e293b 0%, #0a0f1d 55%, #050811 100%);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow-x: hidden;
      padding: 24px 16px;
    }

    /* Ambient background glowing orbs */
    .ambient-glow {
      position: absolute;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      filter: blur(140px);
      pointer-events: none;
      z-index: 0;
      opacity: 0.25;
      animation: pulseGlow 8s ease-in-out infinite alternate;
    }
    .ambient-glow-1 {
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--accent-orange);
    }
    .ambient-glow-2 {
      bottom: -150px;
      right: 10%;
      background: #0284c7;
    }

    @keyframes pulseGlow {
      0% { opacity: 0.18; transform: translateX(-50%) scale(0.9); }
      100% { opacity: 0.32; transform: translateX(-50%) scale(1.1); }
    }

    .container {
      max-width: 860px;
      margin: 0 auto;
      width: 100%;
      position: relative;
      z-index: 10;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      flex: 1;
      padding: 40px 0;
    }

    /* Clinic Branding */
    .brand-logo-container {
      display: inline-flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 28px;
      padding: 10px 24px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid var(--card-border);
      border-radius: 9999px;
      backdrop-filter: blur(12px);
    }
    .brand-logo-container img {
      width: 38px;
      height: 38px;
      object-fit: contain;
      border-radius: 50%;
      background: #ffffff;
      padding: 2px;
    }
    .brand-name {
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.5px;
      color: #ffffff;
    }

    /* Status Beacon */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #fb923c;
      background: rgba(234, 88, 12, 0.12);
      border: 1px solid rgba(234, 88, 12, 0.3);
      padding: 6px 16px;
      border-radius: 9999px;
      margin-bottom: 24px;
    }
    .status-dot {
      width: 8px;
      height: 8px;
      background: #f97316;
      border-radius: 50%;
      box-shadow: 0 0 12px #f97316;
      animation: blink 1.8s infinite ease-in-out;
    }
    @keyframes blink {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(0.75); }
    }

    /* Headings */
    h1 {
      font-size: clamp(32px, 6vw, 54px);
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 18px;
      letter-spacing: -0.5px;
      background: linear-gradient(180deg, #ffffff 0%, #cbd5e1 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    h1 span {
      background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .description {
      font-size: clamp(15px, 2.5vw, 18px);
      line-height: 1.6;
      color: var(--text-muted);
      max-width: 620px;
      margin: 0 auto 36px auto;
    }

    /* Countdown Timer Card */
    .countdown-card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 20px;
      padding: 24px 28px;
      backdrop-filter: blur(16px);
      box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
      margin-bottom: 36px;
      width: 100%;
      max-width: 580px;
    }
    .countdown-title {
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #94a3b8;
      margin-bottom: 18px;
    }
    .timer-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }
    .timer-unit {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 12px;
      padding: 14px 6px;
      transition: transform 0.2s;
    }
    .timer-unit:hover {
      transform: translateY(-2px);
      border-color: rgba(242, 107, 42, 0.4);
    }
    .timer-number {
      font-size: clamp(26px, 4vw, 36px);
      font-weight: 800;
      color: #ffffff;
      line-height: 1;
      font-variant-numeric: tabular-nums;
    }
    .timer-label {
      font-size: 11px;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-top: 6px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* Emergency Contact Panel */
    .action-panel {
      width: 100%;
      max-width: 660px;
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 18px;
      padding: 24px 20px;
      margin-bottom: 24px;
    }
    .action-panel-heading {
      font-size: 14px;
      color: #e2e8f0;
      font-weight: 600;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .action-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 12px;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 9px;
      padding: 12px 22px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.25s ease;
      cursor: pointer;
    }
    .btn-call {
      background: var(--accent-orange);
      color: #ffffff;
      box-shadow: 0 4px 18px rgba(242, 107, 42, 0.35);
    }
    .btn-call:hover {
      background: var(--accent-orange-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 22px rgba(242, 107, 42, 0.45);
    }
    .btn-whatsapp {
      background: #25D366;
      color: #ffffff;
      box-shadow: 0 4px 18px rgba(37, 211, 102, 0.25);
    }
    .btn-whatsapp:hover {
      background: #20bd5a;
      transform: translateY(-2px);
      box-shadow: 0 6px 22px rgba(37, 211, 102, 0.35);
    }
    .btn-email {
      background: rgba(255, 255, 255, 0.07);
      color: #e2e8f0;
      border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .btn-email:hover {
      background: rgba(255, 255, 255, 0.13);
      color: #ffffff;
      transform: translateY(-2px);
    }


    /* Footer */
    footer {
      position: relative;
      z-index: 10;
      text-align: center;
      font-size: 12px;
      color: #64748b;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      max-width: 860px;
      margin: 0 auto;
      width: 100%;
    }
    footer a {
      color: #94a3b8;
      text-decoration: none;
      transition: color 0.2s;
    }
    footer a:hover {
      color: var(--accent-orange);
    }

    @media (max-width: 520px) {
      .timer-grid {
        gap: 8px;
      }
      .timer-unit {
        padding: 10px 4px;
      }
      .action-buttons {
        flex-direction: column;
      }
      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- Ambient Light Orbs -->
  <div class="ambient-glow ambient-glow-1"></div>
  <div class="ambient-glow ambient-glow-2"></div>

  <!-- Main Container -->
  <div class="container">

    <!-- Brand Logo -->
    <div class="brand-logo-container">
      <img src="assets/images/logo.png" alt="<?php echo htmlspecialchars($CLINIC_NAME); ?> Logo" onerror="this.style.display='none'">
      <span class="brand-name"><?php echo htmlspecialchars($CLINIC_NAME); ?></span>
    </div>

    <!-- Pulsing Status Beacon -->
    <div class="status-badge">
      <span class="status-dot"></span>
      Scheduled Maintenance in Progress
    </div>

    <!-- Headlines -->
    <h1>We're Upgrading Our <span>Smile Experience</span></h1>
    <p class="description">
      Our website is currently undergoing scheduled optimization to bring you enhanced clinic services, faster online booking, and new aesthetic features. We'll be back online shortly!
    </p>

    <!-- Dynamic Live Countdown -->
    <div class="countdown-card">
      <div class="countdown-title">Estimated Time Remaining</div>
      <div class="timer-grid">
        <div class="timer-unit">
          <div class="timer-number" id="days">00</div>
          <div class="timer-label">Days</div>
        </div>
        <div class="timer-unit">
          <div class="timer-number" id="hours">00</div>
          <div class="timer-label">Hours</div>
        </div>
        <div class="timer-unit">
          <div class="timer-number" id="minutes">00</div>
          <div class="timer-label">Minutes</div>
        </div>
        <div class="timer-unit">
          <div class="timer-number" id="seconds">00</div>
          <div class="timer-label">Seconds</div>
        </div>
      </div>
    </div>

    <!-- Emergency Clinic Care Contacts -->
    <div class="action-panel">
      <div class="action-panel-heading">
        <i data-lucide="shield-alert" style="width: 18px; height: 18px; color: #f97316;"></i>
        Need Immediate Assistance or Dental Consultation?
      </div>
      <div class="action-buttons">
        <a href="tel:<?php echo preg_replace('/[^\d+]/', '', $CLINIC_PHONE_1); ?>" class="btn btn-call">
          <i data-lucide="phone-call" style="width: 16px; height: 16px;"></i>
          Call Clinic (<?php echo htmlspecialchars($CLINIC_PHONE_1); ?>)
        </a>
        <a href="https://wa.me/<?php echo urlencode($WHATSAPP_NUMBER); ?>?text=<?php echo urlencode('Hello Dr. GR Dental, I need assistance while your website is undergoing maintenance.'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp">
          <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
          WhatsApp Us
        </a>
        <a href="mailto:<?php echo htmlspecialchars($CLINIC_EMAIL); ?>" class="btn btn-email">
          <i data-lucide="mail" style="width: 16px; height: 16px;"></i>
          Email Support
        </a>
      </div>
    </div>


  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($CLINIC_NAME); ?>, <?php echo htmlspecialchars($CLINIC_LOCATION); ?>. All rights reserved.</p>
    <p style="margin-top: 6px; font-style: italic; color: #475569;">"Healthy Smiles, Brighter Tomorrows"</p>
  </footer>

  <script>
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }

    // Dynamic Countdown Timer
    const targetDate = new Date("<?php echo date('Y-m-d\TH:i:s', strtotime($MAINTENANCE_UNTIL)); ?>").getTime();

    function updateCountdown() {
      const now = new Date().getTime();
      let diff = targetDate - now;

      if (diff < 0) {
        diff = (3 * 3600 + 45 * 60) * 1000; // Reset fallback
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      document.getElementById('days').textContent = String(days).padStart(2, '0');
      document.getElementById('hours').textContent = String(hours).padStart(2, '0');
      document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
      document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

  </script>
</body>
</html>
