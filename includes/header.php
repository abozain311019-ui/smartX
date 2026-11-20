<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

use SmartX\Database;

$settings     = Database::getSettings();
$waNumber     = preg_replace('/\D+/', '', (string) ($settings['whatsapp_number'] ?? '966500000000')) ?? '966500000000';
$phoneDisplay = $settings['phone_display'] ?? '050 000 0000';
$currentPage  = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$pageTitle    = $pageTitle ?? 'SMART X | كاميرات مراقبة وسيارة خدمة متنقلة في جدة';
$pageDesc     = $pageDesc ?? 'SMART X لبيع وتركيب كاميرات المراقبة في جدة عبر سيارات الخدمة المتنقلة.';
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#ffffff">
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%23232c31'/%3E%3Cpath d='M7 7l18 18' stroke='%2348bdca' stroke-width='5' stroke-linecap='round'/%3E%3Cpath d='M25 7L7 25' stroke='%23f36f73' stroke-width='5' stroke-linecap='round'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800;900&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="site-header">
    <div class="wrap header-inner">
      <a class="brand" href="index.php" aria-label="SMART X - الرئيسية">
        <img class="brand-mark" src="assets/logo-mark.png" alt="شعار SMART X">
        <div class="brand-copy">
          <span class="brand-name">SMART <span>X</span></span>
          <small class="brand-sub">جدة • خدمة متنقلة</small>
        </div>
      </a>

      <nav class="nav" id="siteNav" aria-label="التنقل الرئيسي">
        <a href="index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">الرئيسية</a>
        <a href="shop.php" class="nav-link <?= $currentPage === 'shop.php' ? 'active' : '' ?>">المتجر</a>
        <a href="works.php" class="nav-link <?= $currentPage === 'works.php' ? 'active' : '' ?>">أعمالنا</a>
        <a href="checkout.php" class="nav-link <?= $currentPage === 'checkout.php' ? 'active' : '' ?>">طلب السيارة</a>
      </nav>

      <div class="header-actions">
        <button type="button" class="cart-trigger" id="cartTrigger" aria-label="سلة المتجر">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
          <span>السلة</span>
          <strong class="cart-badge" id="cartCount">0</strong>
        </button>

        <a
          href="https://wa.me/<?= htmlspecialchars($waNumber) ?>?text=<?= rawurlencode('مرحباً SMART X، أرغب في الاستفسار عن كاميرات المراقبة وسيارة الخدمة في جدة.') ?>"
          target="_blank"
          rel="noopener noreferrer"
          class="btn btn-whatsapp-outline"
        >
          <span>واتساب مباشر</span>
        </a>

        <button class="menu-toggle" id="menuToggle" type="button" aria-label="فتح القائمة" aria-expanded="false" aria-controls="siteNav">
          <span></span><span></span>
        </button>
      </div>
    </div>
  </header>
