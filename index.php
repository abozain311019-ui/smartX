<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/Database.php';

use SmartX\Database;

$settings = Database::getSettings();
$cameras  = Database::getCameras();
$projects = Database::getProjects();

$waNumber     = preg_replace('/\D+/', '', (string) ($settings['whatsapp_number'] ?? '966500000000')) ?? '966500000000';
$phoneDisplay = $settings['phone_display'] ?? '050 000 0000';

$jeddahZones = [
    'north' => [
        'name' => 'شمال جدة وأبحر',
        'eta'  => 'سيارة الخدمة قريبة منك في القطاع الشمالي',
        'note' => 'متخصصون في الفلل الحديثة والقصور والمجمعات السكنية بتمديد مخفي يحافظ على جمال الواجهة.',
        'districts' => ['أبحر الشمالية', 'أبحر الجنوبية', 'حي الشاطئ', 'حي المرجان', 'حي البساتين', 'حي المحمدية', 'حي النعيم', 'حي النهضة', 'حي الزهراء'],
    ],
    'center' => [
        'name' => 'وسط جدة والتحلية',
        'eta'  => 'وصول سريع لقلب جدة السكني والتجاري',
        'note' => 'تجهيز المعارض، المكاتب الإدارية، العيادات، والمنازل بما يطابق اشتراطات التراخيص.',
        'districts' => ['حي الروضة', 'حي السلامة', 'حي الخالدية', 'حي الحمراء', 'حي الأندلس', 'حي الفيصلية', 'حي الصفا', 'حي المروة', 'حي الربوة'],
    ],
    'east' => [
        'name' => 'شرق جدة والحرمين',
        'eta'  => 'تغطية يومية بسيارات مجهزة بالكامل',
        'note' => 'معاينة مجانية وتركيب مرتب وسريع للمنازل، العمائر السكنية، والمحلات التجارية.',
        'districts' => ['حي السامر', 'حي المنار', 'حي الأجواد', 'حي التيسير', 'حي الواحة', 'حي النخيل', 'حي النسيم', 'حي الفيحاء'],
    ],
    'south' => [
        'name' => 'جنوب جدة والخمرة',
        'eta'  => 'فرق متخصصة للمستودعات والمنشآت الكبرى',
        'note' => 'تغطية المساحات الواسعة، الكاميرات المتحركة بعيدة المدى، وتوثيق بوابات الشحن والمداخل.',
        'districts' => ['حي السنابل', 'حي الأجاويد', 'منطقة الخمرة', 'المدينة الصناعية', 'حي الفلاح', 'حي الحمدانية', 'حي طيبة'],
    ],
];
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#ffffff">
  <meta name="description" content="SMART X لخدمات كاميرات المراقبة في جدة. سيارات الخدمة الميدانية تصلك أينما كنت داخل جدة للمعاينة المجانية والتركيب المخفي الاحترافي.">
  <title>SMART X | كاميرات مراقبة وخدمة ميدانية في جدة</title>
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%23232c31'/%3E%3Cpath d='M7 7l18 18' stroke='%2348bdca' stroke-width='5' stroke-linecap='round'/%3E%3Cpath d='M25 7L7 25' stroke='%23f36f73' stroke-width='5' stroke-linecap='round'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://images.pexels.com">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800;900&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Smooth Scroll Progress Rail -->
  <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

  <!-- Floating Glass Header -->
  <header class="site-header" id="siteHeader">
    <div class="wrap header-shell">
      <a class="brand" href="#top" aria-label="SMART X - الرئيسية">
        <img class="brand-mark" src="assets/logo-mark.png" alt="شعار SMART X">
        <div class="brand-copy">
          <span class="brand-name">SMART <span>X</span></span>
          <small class="brand-sub">كاميرات مراقبة • جدة</small>
        </div>
      </a>

      <nav class="nav" id="siteNav" aria-label="التنقل الرئيسي">
        <a href="#phone-experience" class="nav-link">جرّب المشاهدة</a>
        <a href="#blueprint" class="nav-link">تغطية الموقع</a>
        <a href="#vision-lab" class="nav-link">صفاء الصورة</a>
        <a href="#camera-types" class="nav-link">أنواع الكاميرات</a>
        <a href="#journey" class="nav-link">كيف نشتغل</a>
        <a href="#coverage" class="nav-link">أحياء جدة</a>
      </nav>

      <div class="header-actions">
        <a href="admin.php" class="header-admin-link">الإدارة</a>
        <a class="btn btn-primary magnetic" href="#quote">
          <span>اطلب سيارة الخدمة</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
        </a>
        <button class="menu-toggle" id="menuToggle" type="button" aria-label="فتح القائمة" aria-expanded="false" aria-controls="siteNav">
          <span></span><span></span>
        </button>
      </div>
    </div>
  </header>

  <main id="top">

    <!-- ====================================================================
         01. MONUMENTAL HERO — PURE WHITE LUXURY & HIGH IMPACT ARABIC COPY
         ==================================================================== -->
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero-mesh" aria-hidden="true"></div>
      <div class="wrap">
        <div class="hero-top-row reveal">
          <span class="live-pill">
            <span class="pulse-ring"></span>
            <span>خدمة ميدانية فورية تغطي جميع أحياء جدة</span>
          </span>
          <span class="hero-city-label">معاينة مجانية • تركيب مخفي • ضمان سنتين</span>
        </div>

        <h1 class="hero-title reveal" id="hero-title">
          بيتك ومشروعك في أمان..<br>
          <span class="headline-accent">وسيارة الخدمة توصلك وين ما كنت في جدة.</span>
        </h1>

        <div class="hero-subgrid">
          <div class="hero-copy-col reveal">
            <p class="hero-lede">
              نعاين موقعك على الطبيعة، نختار معك الأنسب بدون أي تكاليف زائدة، نرتب التمديد بدون ما يبان سلك واحد، ونسلمك الصورة صافية على جوالك في نفس اليوم.
            </p>
            <div class="hero-cta-row">
              <a class="btn btn-primary btn-xl magnetic" href="#quote">
                <span>اطلب زيارة المعاينة المجانية</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
              </a>
              <a class="btn btn-outline btn-xl" href="#phone-experience">
                <span>شاهد تجربتها على الجوال</span>
              </a>
            </div>
          </div>

          <div class="hero-pillars reveal">
            <div class="pillar-card">
              <strong>نوصلك لباب الموقع</strong>
              <span>سيارات مجهزة تجوب أحياء جدة يومياً</span>
            </div>
            <div class="pillar-card">
              <strong>شغل نظيف ومخفي</strong>
              <span>نحافظ على فخامة ديكور وواجهة مبناك</span>
            </div>
            <div class="pillar-card">
              <strong>راحة بال على جوالك</strong>
              <span>بث حي وتنبيهات فورية وين ما سافرت</span>
            </div>
          </div>
        </div>

        <!-- Monumental Stage Showcase -->
        <figure class="hero-stage reveal">
          <img src="https://images.pexels.com/photos/4391469/pexels-photo-4391469.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=2000" alt="سيارة الخدمة الميدانية SMART X أمام مبنى حديث في جدة" width="2000" height="1200" fetchpriority="high">
          <div class="stage-glass-top">
            <span class="glass-badge"><i class="dot-teal"></i> أسطول SMART X الميداني — جدة</span>
            <span class="glass-badge glass-dark">معاينة • تصميم • تركيب • تسليم فوري</span>
          </div>
          <div class="stage-glass-bottom">
            <div class="floating-panel">
              <div class="panel-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              </div>
              <div>
                <strong>نخدم جميع أحياء جدة</strong>
                <small>من أبحر والشاطئ شمالاً حتى الخمرة جنوباً</small>
              </div>
            </div>
            <div class="floating-panel panel-ink">
              <div>
                <strong>ضمان حقيقي لمدة سنتين</strong>
                <small>على الأجهزة والتركيب مع صيانة ميدانية سريعة</small>
              </div>
            </div>
          </div>
        </figure>
      </div>
    </section>

    <!-- ====================================================================
         02. LIVE INTERACTIVE MOBILE SURVEILLANCE EXPERIENCE (SHOWSTOPPER!)
         ==================================================================== -->
    <section class="phone-section" id="phone-experience" aria-labelledby="phone-title">
      <div class="wrap">
        <div class="phone-showcase-grid">
          <div class="phone-copy-col reveal">
            <span class="kicker"><span class="kicker-num">01</span> موقعك في جيبك 24 ساعة</span>
            <h2 id="phone-title">اطمئن على بيتك ومشروعك<br><em>بلمسة واحدة من جوالك.</em></h2>
            <p class="section-desc">
              بعد ما ننتهي من التركيب، نربط لك المنظومة بالكامل على جوالك وجوالات العائلة أو الإدارة. جرّب التبديل بين الكاميرات في شاشة الجوال أمامك لتشوف كيف تتابع كل زاوية بوضوح فائق.
            </p>

            <div class="channel-switcher" role="tablist" aria-label="تبديل كاميرات العرض المباشر">
              <button type="button" class="channel-btn active" data-channel="front">
                <span class="ch-num">01</span>
                <div>
                  <strong>الواجهة والمدخل الرئيسي</strong>
                  <small>رؤية ليلية ملونة 4K • تغطية عريضة للباب والشارع</small>
                </div>
              </button>
              <button type="button" class="channel-btn" data-channel="villa">
                <span class="ch-num">02</span>
                <div>
                  <strong>محيط الفيلا والأسوار</strong>
                  <small>تنبيه فوري عند اقتراب أي شخص من السور</small>
                </div>
              </button>
              <button type="button" class="channel-btn" data-channel="shop">
                <span class="ch-num">03</span>
                <div>
                  <strong>المعرض والمكاتب الداخلية</strong>
                  <small>صوت وصورة نقية • متابعة سير العمل لحظة بلحظة</small>
                </div>
              </button>
              <button type="button" class="channel-btn" data-channel="gate">
                <span class="ch-num">04</span>
                <div>
                  <strong>البوابة وموقف السيارات</strong>
                  <small>توثيق واضح لدخول وخروج المركبات ليلاً ونهاراً</small>
                </div>
              </button>
            </div>
          </div>

          <!-- Realistic Interactive Smartphone Mockup -->
          <div class="phone-stage reveal">
            <div class="phone-ambient-ring" aria-hidden="true"></div>
            <div class="phone-device" id="phoneDevice">
              <div class="phone-notch"><span></span></div>
              <div class="phone-screen">
                <div class="phone-app-bar">
                  <div>
                    <strong class="app-brand">SMART <span>X</span></strong>
                    <small id="phoneCamSubtitle">الواجهة والمدخل الرئيسي</small>
                  </div>
                  <span class="app-live-tag"><i></i> بث مباشر</span>
                </div>

                <div class="phone-feed-box">
                  <img id="phoneFeedImg" src="https://images.pexels.com/photos/37591155/pexels-photo-37591155.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=1200" alt="معاينة البث المباشر على الجوال">
                  <div class="feed-meta-top">
                    <span id="phoneFeedRes">4K • ملون</span>
                    <span id="phoneClock">23:15:00</span>
                  </div>
                  <div class="feed-toast" id="phoneFeedToast">
                    <span class="toast-dot"></span>
                    <span id="phoneToastText">النظام يعمل بكفاءة — لا توجد تنبيهات طارئة</span>
                  </div>
                </div>

                <div class="phone-controls-row">
                  <div class="p-ctrl"><span>التسجيل</span><strong>مستمر 24/7</strong></div>
                  <div class="p-ctrl"><span>الصوت</span><strong>مفعّل ونقي</strong></div>
                  <div class="p-ctrl"><span>التنبيهات</span><strong>فورية</strong></div>
                </div>

                <div class="phone-mini-grid">
                  <button type="button" class="mini-cam active" data-channel="front">المدخل</button>
                  <button type="button" class="mini-cam" data-channel="villa">الأسوار</button>
                  <button type="button" class="mini-cam" data-channel="shop">المعرض</button>
                  <button type="button" class="mini-cam" data-channel="gate">المواقف</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         03. INTERACTIVE ARCHITECTURAL BLUEPRINT
         ==================================================================== -->
    <section class="blueprint-section" id="blueprint" aria-labelledby="bp-title">
      <div class="wrap">
        <div class="section-header-editorial reveal">
          <div>
            <span class="kicker"><span class="kicker-num">02</span> توزيع مدروس لكل زاوية</span>
            <h2 id="bp-title">نغطي موقعك بالكامل<br><em>بدون ما نترك أي نقطة عمياء.</em></h2>
          </div>
          <p class="section-desc">
            الكاميرا الصح في المكان الصح تغنيك عن كثرة الأجهزة. اضغط على أي رقم في المخطط لتشوف كيف نرتب التغطية في البيوت والمنشآت.
          </p>
        </div>

        <div class="blueprint-grid reveal">
          <div class="blueprint-canvas-card glow-card">
            <div class="bp-toolbar">
              <span class="bp-tag">اضغط على أي نقطة في المخطط لاستعراض زاوية التغطية</span>
              <span class="bp-active-badge" id="bpActiveName">البوابة والمدخل الرئيسي</span>
            </div>

            <div class="bp-svg-wrap">
              <svg viewBox="0 0 800 490" class="bp-svg" role="img" aria-label="مخطط توزيع كاميرات المراقبة">
                <defs>
                  <pattern id="archGrid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(21,119,134,0.09)" stroke-width="1"/>
                  </pattern>
                  <radialGradient id="coneTeal" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="rgba(72,189,202,0.44)"/>
                    <stop offset="100%" stop-color="rgba(72,189,202,0.02)"/>
                  </radialGradient>
                  <radialGradient id="coneCoral" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="rgba(243,111,115,0.46)"/>
                    <stop offset="100%" stop-color="rgba(243,111,115,0.02)"/>
                  </radialGradient>
                </defs>
                <rect width="800" height="490" fill="url(#archGrid)"/>
                <rect x="60" y="50" width="680" height="390" rx="18" fill="#ffffff" stroke="#232c31" stroke-width="3"/>
                <rect x="170" y="120" width="410" height="240" rx="10" fill="#f3f9fa" stroke="#157786" stroke-width="2.4"/>
                <line x1="360" y1="120" x2="360" y2="360" stroke="#c3dbe0" stroke-width="1.5" stroke-dasharray="6 4"/>
                <line x1="170" y1="240" x2="580" y2="240" stroke="#c3dbe0" stroke-width="1.5" stroke-dasharray="6 4"/>
                <rect x="605" y="155" width="110" height="190" rx="10" fill="#fafcfc" stroke="#99b7be" stroke-width="1.8" stroke-dasharray="5 5"/>

                <text x="470" y="185" fill="#5d7179" font-size="14" font-weight="700" text-anchor="middle">الصالة والمجالس</text>
                <text x="265" y="185" fill="#5d7179" font-size="14" font-weight="700" text-anchor="middle">الممرات الداخلية</text>
                <text x="470" y="310" fill="#5d7179" font-size="14" font-weight="700" text-anchor="middle">المدخل الداخلي</text>
                <text x="660" y="255" fill="#5d7179" font-size="13" font-weight="700" text-anchor="middle">موقف السيارات</text>
                <text x="400" y="420" fill="#157786" font-size="14" font-weight="800" text-anchor="middle">الواجهة الأمامية والشارع</text>

                <path id="cone-gate" class="bp-cone active" d="M 400 440 L 250 350 L 550 350 Z" fill="url(#coneCoral)"/>
                <path id="cone-perimeter" class="bp-cone" d="M 75 65 L 290 65 L 140 275 Z" fill="url(#coneTeal)"/>
                <path id="cone-indoor" class="bp-cone" d="M 360 240 L 490 135 L 540 275 Z" fill="url(#coneTeal)"/>
                <path id="cone-garage" class="bp-cone" d="M 725 155 L 570 185 L 630 345 Z" fill="url(#coneCoral)"/>
                <path id="cone-roof" class="bp-cone" d="M 580 120 L 730 55 L 730 215 Z" fill="url(#coneTeal)"/>

                <g class="bp-node active" data-bp="gate" tabindex="0">
                  <circle cx="400" cy="440" r="22" class="node-pulse"/>
                  <circle cx="400" cy="440" r="12" class="node-core"/>
                  <text x="400" y="445" fill="#fff" font-size="11" font-weight="800" text-anchor="middle">1</text>
                </g>
                <g class="bp-node" data-bp="perimeter" tabindex="0">
                  <circle cx="75" cy="65" r="22" class="node-pulse"/>
                  <circle cx="75" cy="65" r="12" class="node-core"/>
                  <text x="75" y="70" fill="#fff" font-size="11" font-weight="800" text-anchor="middle">2</text>
                </g>
                <g class="bp-node" data-bp="indoor" tabindex="0">
                  <circle cx="360" cy="240" r="22" class="node-pulse"/>
                  <circle cx="360" cy="240" r="12" class="node-core"/>
                  <text x="360" y="245" fill="#fff" font-size="11" font-weight="800" text-anchor="middle">3</text>
                </g>
                <g class="bp-node" data-bp="garage" tabindex="0">
                  <circle cx="725" cy="155" r="22" class="node-pulse"/>
                  <circle cx="725" cy="155" r="12" class="node-core"/>
                  <text x="725" y="160" fill="#fff" font-size="11" font-weight="800" text-anchor="middle">4</text>
                </g>
                <g class="bp-node" data-bp="roof" tabindex="0">
                  <circle cx="580" cy="120" r="22" class="node-pulse"/>
                  <circle cx="580" cy="120" r="12" class="node-core"/>
                  <text x="580" y="125" fill="#fff" font-size="11" font-weight="800" text-anchor="middle">5</text>
                </g>
              </svg>
            </div>

            <div class="bp-legend">
              <button type="button" class="bp-tab active" data-bp="gate">01 البوابة الرئيسية</button>
              <button type="button" class="bp-tab" data-bp="perimeter">02 زوايا السور</button>
              <button type="button" class="bp-tab" data-bp="indoor">03 الصالات الداخلية</button>
              <button type="button" class="bp-tab" data-bp="garage">04 موقف السيارات</button>
              <button type="button" class="bp-tab" data-bp="roof">05 التغطية الشاملة 360</button>
            </div>
          </div>

          <div class="blueprint-detail-card glow-card">
            <span class="detail-step" id="bpStepNum">النقطة 01 من 05</span>
            <h3 id="bpTitle">البوابة والمدخل الرئيسي</h3>
            <p id="bpDesc">أهم نقطة في أي بيت أو منشأة. نركب هنا كاميرا بولت أو بانوراما عريضة برؤية ليلية ملونة، تكشف لك الباب والشارع بوضوح تام وتنبهك فوراً على جوالك.</p>

            <div class="detail-specs">
              <div class="d-spec">
                <span>الكاميرا الأنسب</span>
                <strong id="bpCamType">بانوراما 180 درجة / بولت 4K ملونة</strong>
              </div>
              <div class="d-spec">
                <span>مساحة التغطية</span>
                <strong id="bpCoverage">تغطية عريضة للواجهة والمدخل حتى 60 متر</strong>
              </div>
              <div class="d-spec">
                <span>أبرز فائدة لك</span>
                <strong id="bpFeature">إشعار فوري على الجوال عند اقتراب أي شخص من الباب</strong>
              </div>
            </div>

            <a href="#quote" class="btn btn-dark btn-block">اطلب معاينة وتخطيط لموقعك مجاناً</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         04. INTERACTIVE 4K CLARITY COMPARISON
         ==================================================================== -->
    <section class="vision-section" id="vision-lab" aria-labelledby="vision-title">
      <div class="wrap">
        <div class="section-header-editorial reveal">
          <div>
            <span class="kicker"><span class="kicker-num">03</span> صفاء الصورة في الليل والنهار</span>
            <h2 id="vision-title">الفرق يبان قدام عينك..<br><em>صورة صافية حتى في عز الليل.</em></h2>
          </div>
          <p class="section-desc">
            حرّك المؤشر يمين ويسار وشوف الفرق بين الكاميرات العادية اللي تضيّع الملامح وبين دقة 4K الملونة اللي نركبها لك.
          </p>
        </div>

        <div class="vision-stage-shell glow-card reveal">
          <div class="vision-mode-bar">
            <button type="button" class="v-mode active" data-vision-preset="colorvu">الرؤية الليلية الملونة 4K</button>
            <button type="button" class="v-mode" data-vision-preset="ai">تحديد الحركة والمركبات</button>
            <button type="button" class="v-mode" data-vision-preset="zoom">تقريب التفاصيل البعيدة</button>
          </div>

          <div class="comparison-stage" id="comparisonStage">
            <div class="comp-layer comp-after">
              <img src="https://images.pexels.com/photos/37591155/pexels-photo-37591155.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=1800" alt="تصوير SMART X بدقة 4K">
              <div class="comp-badge badge-after">
                <strong>تصوير SMART X بدقة 4K ملونة</strong>
                <small>ألوان طبيعية • ملامح ولوحات واضحة في الليل والنهار</small>
              </div>
              <div class="vision-ai-overlay" id="visionAiOverlay">
                <div class="track-box box-a"><span>تغطية المدخل الرئيسي</span></div>
                <div class="track-box box-b"><span>رصد وتوثيق مركبة</span></div>
              </div>
            </div>

            <div class="comp-layer comp-before" id="compBeforeLayer" style="width: 48%;">
              <img src="https://images.pexels.com/photos/37591155/pexels-photo-37591155.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=1800" alt="كاميرا تقليدية">
              <div class="comp-badge badge-before">
                <strong>الكاميرات العادية القديمة</strong>
                <small>أبيض وأسود باهت • تفاصيل غير واضحة عند الحاجة</small>
              </div>
            </div>

            <div class="comp-handle" id="compHandle" style="left: 48%;" aria-hidden="true">
              <div class="comp-knob">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </div>
            </div>

            <input type="range" min="5" max="95" value="48" class="comp-range" id="compRange" aria-label="سلايدر مقارنة وضوح الكاميرات">
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         05. ASYMMETRIC BENTO CAMERA SHOWCASE (Editorial Luxury, Zero Prices)
         ==================================================================== -->
    <section class="camera-section" id="camera-types" aria-labelledby="camera-title">
      <div class="wrap">
        <div class="section-header-editorial reveal">
          <div>
            <span class="kicker"><span class="kicker-num">04</span> أحدث أنواع الكاميرات</span>
            <h2 id="camera-title">اختر اللي يناسب مكانك..<br><em>والباقي علينا من الألف للياء.</em></h2>
          </div>
          <div class="filter-bar" role="tablist" aria-label="تصفية أنواع الكاميرات">
            <button type="button" class="filter-pill active" data-filter="all">جميع الأنواع</button>
            <button type="button" class="filter-pill" data-filter="outdoor">خارجية</button>
            <button type="button" class="filter-pill" data-filter="indoor">داخلية</button>
            <button type="button" class="filter-pill" data-filter="ptz">متحركة 360</button>
            <button type="button" class="filter-pill" data-filter="solar">طاقة شمسية</button>
            <button type="button" class="filter-pill" data-filter="anpr">قراءة لوحات</button>
          </div>
        </div>

        <div class="bento-camera-grid" id="cameraGrid">
          <?php foreach ($cameras as $idx => $cam): ?>
            <article class="cam-card glow-card reveal <?= $idx === 0 || $idx === 3 ? 'cam-featured-wide' : '' ?>" data-category="<?= htmlspecialchars($cam['category']) ?>">
              <div class="cam-media">
                <img src="<?= htmlspecialchars($cam['image_url']) ?>" alt="<?= htmlspecialchars($cam['name_ar']) ?>" loading="lazy" decoding="async">
                <span class="cam-badge"><?= htmlspecialchars($cam['badge']) ?></span>
                <span class="cam-code"><?= htmlspecialchars($cam['code']) ?></span>
              </div>

              <div class="cam-body">
                <h3><?= htmlspecialchars($cam['name_ar']) ?></h3>
                <p class="cam-desc"><?= htmlspecialchars($cam['description']) ?></p>

                <dl class="cam-specs">
                  <div><dt>الوضوح:</dt><dd><?= htmlspecialchars($cam['resolution']) ?></dd></div>
                  <div><dt>الرؤية الليلية:</dt><dd><?= htmlspecialchars($cam['night_vision']) ?></dd></div>
                  <div><dt>التغطية:</dt><dd><?= htmlspecialchars($cam['fov']) ?></dd></div>
                </dl>

                <button
                  type="button"
                  class="btn btn-outline btn-block select-camera-btn"
                  data-cam-name="<?= htmlspecialchars($cam['name_ar']) ?>"
                >
                  <span>اطلب معاينة لهذا النوع</span>
                </button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         06. STICKY STACKING JOURNEY (Modern Apple/Linear Scroll-Pinning Trend!)
         ==================================================================== -->
    <section class="journey-section" id="journey" aria-labelledby="journey-title">
      <div class="wrap journey-layout">
        <div class="journey-sticky-col reveal">
          <span class="kicker"><span class="kicker-num">05</span> خطوات واضحة ومريحة</span>
          <h2 id="journey-title">من لحظة تواصلك<br><em>حتى تستلم الصورة على جوالك.</em></h2>
          <p class="section-desc">
            ما تشيل هم الأسلاك ولا اختيار الأجهزة ولا البرمجة. نمر معك بأربع خطوات مرتبة تضمن لك نتيجة تبيض الوجه وتعيش معك سنين.
          </p>
          <a href="#quote" class="btn btn-primary btn-lg magnetic" style="margin-top: 24px;">
            <span>ابدأ بحجز المعاينة المجانية</span>
          </a>
        </div>

        <div class="journey-cards-col">
          <article class="stack-card glow-card" style="--card-index: 1;">
            <span class="stack-num">01</span>
            <h3>تصلك سيارة الخدمة للمعاينة المجانية</h3>
            <p>نمر موقعك في أي حي بجدة، نعاين المداخل والزوايا على الطبيعة، ونقترح عليك التوزيع والعدد المثالي بدون أي التزام مالي.</p>
          </article>

          <article class="stack-card glow-card" style="--card-index: 2;">
            <span class="stack-num">02</span>
            <h3>تمديد مخفي يحافظ على جمال المكان</h3>
            <p>نستخدم كابلات نحاسية أصلية ومواسير حماية ضد شمس ورطوبة جدة، مع إخفاء كامل للأسلاك عشان تبقى الواجهة والديكور بأجمل صورة.</p>
          </article>

          <article class="stack-card glow-card" style="--card-index: 3;">
            <span class="stack-num">03</span>
            <h3>برمجة كاملة وربط مباشر على جوالك</h3>
            <p>نضبط لك التسجيل المستمر وتنبيهات الحركة، ونربط الكاميرات بجوالك وجوالات العائلة أو الإدارة، ونشرح لك استخدام التطبيق بكل بساطة.</p>
          </article>

          <article class="stack-card glow-card" style="--card-index: 4;">
            <span class="stack-num">04</span>
            <h3>ضمان شامل سنتين وخدمة قريبة منك</h3>
            <p>علاقتنا ما تنتهي عند التركيب؛ نقدم لك ضماناً حقيقياً لمدة سنتين وفريقنا الميداني في جدة جاهز لخدمتك ودعمك في أي وقت.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         07. PORTFOLIO / REAL SOLUTIONS IN JEDDAH
         ==================================================================== -->
    <section class="work-section" id="works" aria-labelledby="works-title">
      <div class="wrap">
        <div class="section-header-editorial reveal">
          <div>
            <span class="kicker"><span class="kicker-num">06</span> حلولنا على أرض الواقع</span>
            <h2 id="works-title">شغل يليق بمكانة<br><em>بيتك أو مشروعك في جدة.</em></h2>
          </div>
          <p class="section-desc">سواء كانت فيلا سكنية حديثة، معرض تجاري، أو مستودع كبير؛ نصمم التغطية على مقاس المكان بالضبط.</p>
        </div>

        <div class="work-grid">
          <?php foreach ($projects as $proj): ?>
            <article class="work-card glow-card reveal">
              <div class="work-media">
                <img src="<?= htmlspecialchars($proj['image_url']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>" loading="lazy" decoding="async">
                <span class="work-tag"><?= htmlspecialchars($proj['tag']) ?></span>
                <span class="work-loc"><?= htmlspecialchars($proj['district']) ?></span>
              </div>
              <div class="work-content">
                <div class="work-meta"><?= htmlspecialchars($proj['system_spec']) ?></div>
                <h3><?= htmlspecialchars($proj['title']) ?></h3>
                <p><?= htmlspecialchars($proj['summary']) ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         08. JEDDAH NEIGHBORHOOD COVERAGE
         ==================================================================== -->
    <section class="coverage-section" id="coverage" aria-labelledby="cov-title">
      <div class="wrap">
        <div class="coverage-shell glow-card reveal">
          <div class="coverage-head">
            <div>
              <span class="kicker"><span class="kicker-num">07</span> نوصلك في كل جدة</span>
              <h2 id="cov-title">اختر حيّك.. <em>ونكون عندك.</em></h2>
              <p>اضغط على اسم الحي لنجهز لك طلب زيارة سيارة الخدمة مباشرة.</p>
            </div>
            <div class="zone-tabs" role="tablist">
              <?php $firstZone = true; foreach ($jeddahZones as $zKey => $zone): ?>
                <button type="button" class="zone-tab <?= $firstZone ? 'active' : '' ?>" data-zone="<?= htmlspecialchars($zKey) ?>">
                  <?= htmlspecialchars($zone['name']) ?>
                </button>
              <?php $firstZone = false; endforeach; ?>
            </div>
          </div>

          <?php $firstZone = true; foreach ($jeddahZones as $zKey => $zone): ?>
            <div class="zone-panel <?= $firstZone ? 'active' : '' ?>" id="zone-<?= htmlspecialchars($zKey) ?>">
              <div class="zone-banner">
                <strong><?= htmlspecialchars($zone['eta']) ?></strong>
                <span><?= htmlspecialchars($zone['note']) ?></span>
              </div>
              <div class="districts-cloud">
                <?php foreach ($zone['districts'] as $dist): ?>
                  <button type="button" class="district-chip" data-district="<?= htmlspecialchars($dist) ?>">
                    <span><?= htmlspecialchars($dist) ?></span>
                    <small>اطلب زيارة ←</small>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>
          <?php $firstZone = false; endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         09. FIELD VISIT REQUEST FORM (PHP SQLite + Instant WhatsApp)
         ==================================================================== -->
    <section class="quote-section" id="quote" aria-labelledby="quote-title">
      <div class="wrap quote-grid">
        <div class="quote-editorial reveal">
          <span class="kicker"><span class="kicker-num">08</span> احجز زيارتك الآن</span>
          <h2 id="quote-title">خلّنا نعاين موقعك<br><em>ونضبط لك الأمان صح.</em></h2>
          <p>اكتب بياناتك في ثوانٍ، وراح يتسجل طلبك عندنا برقم متابعة وتنفتح لك محادثة واتساب مباشرة مع فريق الخدمة الميدانية في جدة لتحديد الوقت اللي يناسبك.</p>

          <div class="quote-steps">
            <div class="q-step">
              <span>01</span>
              <div>
                <strong>معاينة مجانية في موقعك بجدة</strong>
                <small>بدون أي التزام — نشوف المكان ونعطيك الرأي الصادق والأنسب</small>
              </div>
            </div>
            <div class="q-step">
              <span>02</span>
              <div>
                <strong>تركيب نظيف وضمان سنتين</strong>
                <small>تستلم النظام شغال ومربوط على جوالك بأعلى جودة</small>
              </div>
            </div>
          </div>
        </div>

        <form class="quote-form glow-card reveal" id="qf" novalidate>
          <div class="form-head">
            <span class="form-kicker">SMART X — الخدمة الميدانية في جدة</span>
            <h3>طلب زيارة سيارة الخدمة</h3>
          </div>

          <div class="form-fields">
            <div class="field">
              <label for="n">الاسم الكريم</label>
              <input id="n" name="name" autocomplete="name" placeholder="اكتب اسمك الكريم" required>
            </div>

            <div class="field">
              <label for="p">رقم الجوال</label>
              <input id="p" name="phone" type="tel" inputmode="tel" autocomplete="tel" dir="ltr" placeholder="05X XXX XXXX" required>
            </div>

            <div class="field">
              <label for="district">الحي في جدة</label>
              <input id="district" name="district" placeholder="مثال: الشاطئ، الروضة، أبحر..." required>
            </div>

            <div class="field">
              <label for="t">نوع المكان</label>
              <select id="t" name="place">
                <option value="منزل أو فيلا">منزل أو فيلا</option>
                <option value="معرض أو مكتب تجاري">معرض أو مكتب تجاري</option>
                <option value="مستودع أو مصنع">مستودع أو مصنع</option>
                <option value="عمارة أو مجمع سكني">عمارة أو مجمع سكني</option>
                <option value="مدرسة أو منشأة">مدرسة أو منشأة</option>
              </select>
            </div>

            <div class="field">
              <label for="c">حجم التغطية المطلوب</label>
              <select id="c" name="cameras">
                <option value="تغطية مداخل أساسية (1 إلى 4 كاميرات)">تغطية مداخل أساسية (1 إلى 4 كاميرات)</option>
                <option value="تغطية شاملة للمبنى (5 إلى 12 كاميرا)" selected>تغطية شاملة للمبنى (5 إلى 12 كاميرا)</option>
                <option value="تغطية منشآت كبرى (أكثر من 12 كاميرا)">تغطية منشآت كبرى (أكثر من 12 كاميرا)</option>
                <option value="يحدد بعد المعاينة المجانية">يحدد بعد المعاينة المجانية</option>
              </select>
            </div>

            <div class="field">
              <label for="prefDate">الوقت اللي يناسبك للزيارة</label>
              <select id="prefDate" name="preferred_date">
                <option value="في أقرب وقت متاح">في أقرب وقت متاح</option>
                <option value="الفترة الصباحية (9 صباحاً - 1 ظهراً)">الفترة الصباحية (9 صباحاً - 1 ظهراً)</option>
                <option value="الفترة المسائية (4 عصراً - 10 مساءً)">الفترة المسائية (4 عصراً - 10 مساءً)</option>
              </select>
            </div>

            <div class="field field-full">
              <label for="d">تفاصيل إضافية <span>(اختياري)</span></label>
              <textarea id="d" name="details" rows="3" placeholder="اكتب أي تفاصيل تحب نعرفها قبل الزيارة..."></textarea>
            </div>
          </div>

          <button class="btn btn-primary btn-xl btn-block magnetic" id="submitBtn" type="submit">
            <span>أرسل الطلب وتواصل معنا عبر واتساب</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
          </button>

          <div id="st" class="form-status" role="status" aria-live="polite"></div>
        </form>
      </div>
    </section>

  </main>

  <footer class="site-footer">
    <div class="footer-accent"><span></span><span></span></div>
    <div class="wrap footer-inner">
      <a class="brand footer-brand" href="#top">
        <img class="brand-mark" src="assets/logo-mark.png" alt="SMART X">
        <span class="brand-name">SMART <span>X</span></span>
      </a>
      <span class="footer-tagline">كاميرات مراقبة وخدمة ميدانية احترافية في جميع أنحاء جدة</span>
      <div class="footer-right">
        <span dir="ltr"><?= htmlspecialchars($phoneDisplay) ?></span>
        <small>© <?= date('Y') ?> SMART X. جميع الحقوق محفوظة.</small>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
