<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/Database.php';

use SmartX\Database;

$settings = Database::getSettings();
$products = Database::getCameras();
$projects = Database::getProjects();

$waNumber     = preg_replace('/\D+/', '', (string) ($settings['whatsapp_number'] ?? '966500000000')) ?? '966500000000';
$phoneDisplay = $settings['phone_display'] ?? '050 000 0000';

$storeCategories = [
    ['id' => 'bundles', 'title' => 'منظومات متكاملة', 'sub' => 'كاميرات + جهاز تسجيل + تركيب', 'img' => 'assets/images/hero-van-jeddah.jpg'],
    ['id' => 'outdoor', 'title' => 'كاميرات خارجية 4K', 'sub' => 'مقاومة لحرارة ورطوبة جدة', 'img' => 'assets/images/cam-bullet.jpg'],
    ['id' => 'indoor',  'title' => 'كاميرات داخلية أنيقة', 'sub' => 'مدمجة في الأسقف مع صوت نقي', 'img' => 'assets/images/cam-dome.jpg'],
    ['id' => 'ptz',     'title' => 'كاميرات متحركة 360', 'sub' => 'تحكم وتقريب بصري من جوالك', 'img' => 'assets/images/cam-ptz.jpg'],
    ['id' => 'solar',   'title' => 'طاقة شمسية لاسلكية', 'sub' => 'تعمل بشريحة 4G بدون أسلاك', 'img' => 'assets/images/cam-solar.jpg'],
    ['id' => 'anpr',    'title' => 'قراءة لوحات السيارات', 'sub' => 'لبوابات المجمعات والمستودعات', 'img' => 'assets/images/cam-anpr-gate.jpg'],
];

$jeddahZones = [
    'north' => [
        'name' => 'شمال جدة وأبحر',
        'eta'  => 'سياراتنا متمركزة شمال جدة للتوصيل والتركيب السريع',
        'note' => 'متخصصون في الفلل الحديثة والقصور والمجمعات السكنية بتمديد مخفي بالكامل.',
        'districts' => ['أبحر الشمالية', 'أبحر الجنوبية', 'حي الشاطئ', 'حي المرجان', 'حي البساتين', 'حي المحمدية', 'حي النعيم', 'حي النهضة', 'حي الزهراء'],
    ],
    'center' => [
        'name' => 'وسط جدة والتحلية',
        'eta'  => 'تغطية مباشرة للقطاع السكني والتجاري في وسط جدة',
        'note' => 'توريد وتركيب معتمد للمعارض، المكاتب الإدارية، العيادات، والمنازل.',
        'districts' => ['حي الروضة', 'حي السلامة', 'حي الخالدية', 'حي الحمراء', 'حي الأندلس', 'حي الفيصلية', 'حي الصفا', 'حي المروة', 'حي الربوة'],
    ],
    'east' => [
        'name' => 'شرق جدة والحرمين',
        'eta'  => 'تغطية يومية بسيارات SMART X المجهزة بالكامل',
        'note' => 'توصيل فوري للمنتجات وتركيب مرتب وسريع في نفس اليوم.',
        'districts' => ['حي السامر', 'حي المنار', 'حي الأجواد', 'حي التيسير', 'حي الواحة', 'حي النخيل', 'حي النسيم', 'حي الفيحاء'],
    ],
    'south' => [
        'name' => 'جنوب جدة والخمرة',
        'eta'  => 'فرق متخصصة للمستودعات والمنشآت والمشاريع الكبرى',
        'note' => 'كاميرات متحركة بعيدة المدى وتوثيق بوابات الشحن والمداخل الصناعية.',
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
  <meta name="description" content="متجر SMART X لكاميرات المراقبة في جدة — اطلب كاميراتك أو منظومتك المتكاملة وتصلك سيارة الخدمة المتنقلة للتوصيل والتركيب المخفي في نفس اليوم.">
  <title>SMART X | متجر كاميرات المراقبة والسيارة المتنقلة في جدة</title>
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%23232c31'/%3E%3Cpath d='M7 7l18 18' stroke='%2348bdca' stroke-width='5' stroke-linecap='round'/%3E%3Cpath d='M25 7L7 25' stroke='%23f36f73' stroke-width='5' stroke-linecap='round'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800;900&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Scroll Progress Rail -->
  <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

  <!-- Top Announcement Ribbon -->
  <div class="top-ribbon">
    <div class="wrap ribbon-inner">
      <div class="ribbon-right">
        <span class="pulse-dot"></span>
        <span>متجر SMART X المتنقل في جدة — التوصيل والتركيب الفوري عبر سياراتنا المجهزة لباب موقعك</span>
      </div>
      <div class="ribbon-left">
        <span dir="ltr"><?= htmlspecialchars($phoneDisplay) ?></span>
        <span class="sep">|</span>
        <a href="admin.php">لوحة الإدارة</a>
      </div>
    </div>
  </div>

  <!-- Sticky Store Navigation -->
  <header class="site-header" id="siteHeader">
    <div class="wrap header-inner">
      <a class="brand" href="#top" aria-label="SMART X - الرئيسية">
        <img class="brand-mark" src="assets/logo-mark.png" alt="شعار SMART X">
        <div class="brand-copy">
          <span class="brand-name">SMART <span>X</span></span>
          <small class="brand-sub">متجر وخدمة متنقلة • جدة</small>
        </div>
      </a>

      <nav class="nav" id="siteNav" aria-label="التنقل الرئيسي">
        <a href="#categories" class="nav-link">أقسام المتجر</a>
        <a href="#store" class="nav-link">المنتجات والمنظومات</a>
        <a href="#van-advantage" class="nav-link">السيارة المتنقلة</a>
        <a href="#works" class="nav-link">عن أعمالنا</a>
        <a href="#vision-lab" class="nav-link">وضوح 4K</a>
        <a href="#coverage" class="nav-link">أحياء جدة</a>
      </nav>

      <div class="header-actions">
        <button type="button" class="cart-trigger" id="cartTrigger" aria-label="فتح سلة طلبات السيارة">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
          <span>سلة الطلب</span>
          <strong class="cart-badge" id="cartCount">0</strong>
        </button>

        <a class="btn btn-primary" href="#checkout">
          <span>اطلب السيارة الآن</span>
        </a>

        <button class="menu-toggle" id="menuToggle" type="button" aria-label="فتح القائمة" aria-expanded="false" aria-controls="siteNav">
          <span></span><span></span>
        </button>
      </div>
    </div>
  </header>

  <main id="top">

    <!-- ====================================================================
         01. STORE HERO — MOBILE VAN SHOWROOM IN JEDDAH
         ==================================================================== -->
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero-mesh" aria-hidden="true"></div>
      <div class="wrap hero-grid">
        <div class="hero-copy reveal">
          <span class="eyebrow">
            <span class="eyebrow-dot"></span>
            <span>المتجر المتنقل الأول لكاميرات المراقبة في جدة</span>
          </span>

          <h1 id="hero-title">
            متجر الكاميرات كامل<br>
            <em>يوصلك لباب موقعك بسيارة الخدمة.</em>
          </h1>

          <p class="hero-lede">
            اختر الكاميرات أو المنظومة اللي تناسب بيتك أو مشروعك من متجر <strong>SMART X</strong>، وسيارتنا المجهزة توصلك في أي حي بجدة: تعاين، تورّد الأجهزة الأصلية، وتركب لك بتمديد مخفي ونظيف في نفس اليوم.
          </p>

          <div class="hero-cta-row">
            <a class="btn btn-primary btn-lg" href="#store">
              <span>تصفح المتجر والمنظومات</span>
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
            </a>
            <a class="btn btn-outline btn-lg" href="#checkout">
              <span>اطلب معاينة مجانية لموقعك</span>
            </a>
          </div>

          <div class="hero-trust-row">
            <div class="trust-pill">
              <strong>توصيل وتركيب في نفس اليوم</strong>
              <span>سيارات مجهزة بجميع الموديلات داخل جدة</span>
            </div>
            <div class="trust-pill">
              <strong>ضمان شامل سنتين</strong>
              <span>أجهزة أصلية مع صيانة ميدانية سريعة</span>
            </div>
            <div class="trust-pill">
              <strong>تمديد مخفي ومعتمد</strong>
              <span>شغل نظيف للفلل ومطابق لتراخيص المتاجر</span>
            </div>
          </div>
        </div>

        <figure class="hero-visual reveal">
          <img src="assets/images/hero-van-jeddah.jpg" alt="سيارة SMART X المتنقلة لبيع وتركيب كاميرات المراقبة أمام فيلا حديثة في جدة" width="1600" height="900" fetchpriority="high">
          <div class="hero-float-top">
            <span class="dot-teal"></span>
            <span>معرض وورشة متنقلة — جدة</span>
          </div>
          <div class="hero-float-bottom">
            <div>
              <strong>اختر من المتجر.. والتركيب علينا</strong>
              <small>لا شحن ينتظر أيام ولا بحث عن فنيين؛ السيارة توصلك جاهزة</small>
            </div>
          </div>
        </figure>
      </div>
    </section>

    <!-- ====================================================================
         02. SHOP BY CATEGORY (Like CameraWorld Storefront, SMART X Identity)
         ==================================================================== -->
    <section class="categories-section" id="categories" aria-labelledby="cat-title">
      <div class="wrap">
        <div class="section-head reveal">
          <div>
            <span class="kicker">تصفح حسب الاحتياج</span>
            <h2 id="cat-title">أقسام متجر <em>SMART X</em></h2>
          </div>
          <p>اختر القسم المناسب لموقعك لتصفح الأجهزة والمنظومات المتوفرة في سيارات الخدمة فوراً.</p>
        </div>

        <div class="categories-grid">
          <?php foreach ($storeCategories as $cat): ?>
            <button type="button" class="category-card reveal" data-jump-category="<?= htmlspecialchars($cat['id']) ?>">
              <div class="cat-thumb">
                <img src="<?= htmlspecialchars($cat['img']) ?>" alt="<?= htmlspecialchars($cat['title']) ?>" loading="lazy">
              </div>
              <div class="cat-info">
                <strong><?= htmlspecialchars($cat['title']) ?></strong>
                <small><?= htmlspecialchars($cat['sub']) ?></small>
              </div>
            </button>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         03. MAIN STOREFRONT — PRODUCTS & SYSTEMS WITH ADD TO VAN CART
         ==================================================================== -->
    <section class="store-section" id="store" aria-labelledby="store-title">
      <div class="wrap">
        <div class="section-head reveal">
          <div>
            <span class="kicker">توريد وتركيب فوري بجدة</span>
            <h2 id="store-title">المنتجات والمنظومات <em>المتوفرة في سياراتنا</em></h2>
          </div>

          <div class="filter-bar" role="tablist" aria-label="تصفية منتجات المتجر">
            <button type="button" class="filter-pill active" data-filter="all">الكل (<?= count($products) ?>)</button>
            <button type="button" class="filter-pill" data-filter="bundles">منظومات متكاملة</button>
            <button type="button" class="filter-pill" data-filter="outdoor">خارجية 4K</button>
            <button type="button" class="filter-pill" data-filter="indoor">داخلية للأسقف</button>
            <button type="button" class="filter-pill" data-filter="ptz">متحركة 360</button>
            <button type="button" class="filter-pill" data-filter="solar">طاقة شمسية</button>
            <button type="button" class="filter-pill" data-filter="anpr">قراءة لوحات</button>
          </div>
        </div>

        <div class="products-grid" id="productGrid">
          <?php foreach ($products as $prod): ?>
            <article class="product-card glow-card reveal" data-category="<?= htmlspecialchars($prod['category']) ?>">
              <div class="product-media">
                <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['name_ar']) ?>" loading="lazy" decoding="async">
                <span class="prod-badge"><?= htmlspecialchars($prod['badge']) ?></span>
                <span class="prod-sku"><?= htmlspecialchars($prod['code']) ?></span>
              </div>

              <div class="product-body">
                <h3><?= htmlspecialchars($prod['name_ar']) ?></h3>
                <p class="prod-desc"><?= htmlspecialchars($prod['description']) ?></p>

                <ul class="prod-specs">
                  <li><span>الدقة والوضوح:</span> <b><?= htmlspecialchars($prod['resolution']) ?></b></li>
                  <li><span>الرؤية الليلية:</span> <b><?= htmlspecialchars($prod['night_vision']) ?></b></li>
                  <li><span>التغطية والتحمل:</span> <b><?= htmlspecialchars($prod['fov']) ?></b></li>
                </ul>

                <div class="product-footer">
                  <div class="prod-availability">
                    <span class="avail-dot"></span>
                    <span>متوفر في سيارة الخدمة — التسعير حسب المعاينة والتركيب</span>
                  </div>
                  <div class="prod-actions">
                    <button
                      type="button"
                      class="btn btn-primary btn-block add-to-cart-btn"
                      data-id="<?= htmlspecialchars($prod['code']) ?>"
                      data-name="<?= htmlspecialchars($prod['name_ar']) ?>"
                      data-img="<?= htmlspecialchars($prod['image_url']) ?>"
                      data-spec="<?= htmlspecialchars($prod['resolution']) ?>"
                    >
                      <span>أضف لسلة سيارة الخدمة</span>
                    </button>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         04. THE MOBILE VAN ADVANTAGE (Why SMART X beats ordinary online stores)
         ==================================================================== -->
    <section class="van-section" id="van-advantage" aria-labelledby="van-title">
      <div class="wrap">
        <div class="van-shell glow-card reveal">
          <div class="van-copy">
            <span class="kicker">ليش فكرة السيارة المتنقلة تفرق معك؟</span>
            <h2 id="van-title">بدل ما تشتري كاميرات وتدور فني..<br><em>المتجر والمهندس يجونك سوا.</em></h2>
            <p>
              في المتاجر التقليدية تشتري الأجهزة وتنتظر الشحن أيام وبعدها تبحث عن فني تركيب وتتحمل فرق الأسلاك والملحقات. مع <strong>SMART X في جدة</strong>، سيارتنا المجهزة تصلك لباب بيتك أو معرضك ومعها الأجهزة والكابلات وأدوات التركيب المخفي، تعاين وتنفذ وتبرمج على جوالك في زيارة واحدة.
            </p>
            <div class="van-steps">
              <div class="v-step">
                <span>01</span>
                <div>
                  <strong>اختر طلبك من المتجر</strong>
                  <small>أضف الكاميرات أو المنظومة لسلة السيارة أو اطلب معاينة مباشرة</small>
                </div>
              </div>
              <div class="v-step">
                <span>02</span>
                <div>
                  <strong>تصلك سيارة SMART X لباب الموقع</strong>
                  <small>معاينة الزوايا وتأكيد الأنسب لمساحة موقعك على الطبيعة</small>
                </div>
              </div>
              <div class="v-step">
                <span>03</span>
                <div>
                  <strong>تركيب مخفي وتسليم جاهز على جوالك</strong>
                  <small>تمديد نظيف، برمجة كاملة، وضمان حقيقي لمدة سنتين</small>
                </div>
              </div>
            </div>
          </div>

          <div class="van-visual-col">
            <img src="assets/images/cam-panorama-dusk.jpg" alt="تركيب كاميرا بانوراما 4K على واجهة فيلا حديثة في جدة" loading="lazy">
            <div class="van-stat-strip">
              <div><strong>معاينة مجانية</strong><span>في جميع أحياء جدة</span></div>
              <div><strong>تمديد مخفي</strong><span>بدون أي أسلاك ظاهرة</span></div>
              <div><strong>ضمان سنتين</strong><span>صيانة ميدانية بموقعك</span></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         05. ABOUT OUR WORKS & COMPLETED SOLUTIONS IN JEDDAH
         ==================================================================== -->
    <section class="work-section" id="works" aria-labelledby="works-title">
      <div class="wrap">
        <div class="section-head reveal">
          <div>
            <span class="kicker">عن أعمالنا في جدة</span>
            <h2 id="works-title">مشاريع نفذناها بمعايير<br><em>تليق ببيتك ومنشأتك.</em></h2>
          </div>
          <p>من الفلل السكنية الفاخرة في الشاطئ وأبحر إلى المعارض والمكاتب والمستودعات، شغلنا يتكلم عن نفسه في نظافة التمديد وصفاء الصورة.</p>
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
         06. INTERACTIVE 4K NIGHT CLARITY PROOF
         ==================================================================== -->
    <section class="vision-section" id="vision-lab" aria-labelledby="vision-title">
      <div class="wrap">
        <div class="section-head reveal">
          <div>
            <span class="kicker">شاهد الفرق بعينك</span>
            <h2 id="vision-title">رؤية ليلية ملونة 4K<br><em>مقابل الكاميرات العادية.</em></h2>
          </div>
          <p>اسحب الفاصل في المنتصف لتشاهد كيف تحافظ كاميرات SMART X على الألوان والتفاصيل واللوحات حتى في الإضاءة الليلية الضعيفة.</p>
        </div>

        <div class="vision-stage-shell glow-card reveal">
          <div class="comparison-stage" id="comparisonStage">
            <div class="comp-layer comp-after">
              <img src="assets/images/cam-panorama-dusk.jpg" alt="تصوير SMART X بدقة 4K ليلية ملونة">
              <div class="comp-badge badge-after">
                <strong>تصوير SMART X بدقة 4K ملونة</strong>
                <small>ألوان ليلية طبيعية • تفاصيل واضحة للمداخل والشارع</small>
              </div>
            </div>

            <div class="comp-layer comp-before" id="compBeforeLayer" style="width: 48%;">
              <img src="assets/images/cam-panorama-dusk.jpg" alt="تصوير كاميرا تقليدية">
              <div class="comp-badge badge-before">
                <strong>الكاميرات العادية القديمة</strong>
                <small>أبيض وأسود باهت • فقدان تفاصيل مهمة</small>
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
         07. JEDDAH COVERAGE ZONES
         ==================================================================== -->
    <section class="coverage-section" id="coverage" aria-labelledby="cov-title">
      <div class="wrap">
        <div class="coverage-shell glow-card reveal">
          <div class="coverage-head">
            <div>
              <span class="kicker">التوصيل والتركيب في كل جدة</span>
              <h2 id="cov-title">اختر حيّك.. <em>وسيارتنا توصلك.</em></h2>
              <p>اضغط على اسم الحي لإضافته مباشرة إلى نموذج إتمام الطلب والتوصيل.</p>
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
                    <small>اختيار الحي ←</small>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>
          <?php $firstZone = false; endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         08. MOBILE VAN CHECKOUT & DISPATCH (Inspired by E-Commerce Checkout)
         ==================================================================== -->
    <section class="checkout-section" id="checkout" aria-labelledby="checkout-title">
      <div class="wrap">
        <div class="section-head reveal">
          <div>
            <span class="kicker">إتمام الطلب والتوصيل والتركيب</span>
            <h2 id="checkout-title">أكمل طلبك.. <em>وسيارة SMART X في طريقها لك.</em></h2>
          </div>
          <p>سواء أضفت منتجات من المتجر أو ترغب بزيارة معاينة مجانية أولاً، أكمل بياناتك وسيتأكد معك فريقنا فوراً.</p>
        </div>

        <div class="checkout-grid">
          <!-- Left/Start: Customer & Delivery Details Form -->
          <form class="checkout-form glow-card reveal" id="qf" novalidate>
            <div class="form-head">
              <h3>بيانات التوصيل والزيارة في جدة</h3>
              <span>يُحفظ طلبك برقم متابعة رسمي ويُرسل مباشرة لواتساب فريق التركيب</span>
            </div>

            <!-- Delivery / Service Type Choice -->
            <div class="delivery-modes" id="deliveryModeGroup">
              <label class="delivery-option active">
                <input type="radio" name="service_mode" value="توصيل وتركيب فوري عبر سيارة SMART X المتنقلة" checked>
                <div>
                  <strong>توصيل وتركيب عبر السيارة المتنقلة</strong>
                  <small>تصلك السيارة بالأجهزة المختارة ونركبها لك في نفس اليوم بجدة</small>
                </div>
              </label>
              <label class="delivery-option">
                <input type="radio" name="service_mode" value="زيارة معاينة مجانية أولاً قبل التوريد">
                <div>
                  <strong>زيارة معاينة مجانية أولاً</strong>
                  <small>نعاين الموقع ونحدد العدد والأنسب لك قبل اعتماد أي شيء</small>
                </div>
              </label>
            </div>

            <div class="form-fields">
              <div class="field">
                <label for="n">الاسم الكريم *</label>
                <input id="n" name="name" autocomplete="name" placeholder="اكتب اسمك الكريم" required>
              </div>

              <div class="field">
                <label for="p">رقم الجوال (واتساب / اتصال) *</label>
                <input id="p" name="phone" type="tel" inputmode="tel" autocomplete="tel" dir="ltr" placeholder="05X XXX XXXX" required>
              </div>

              <div class="field">
                <label for="district">الحي في جدة *</label>
                <input id="district" name="district" placeholder="مثال: الشاطئ، الروضة، أبحر..." required>
              </div>

              <div class="field">
                <label for="t">نوع الموقع</label>
                <select id="t" name="place">
                  <option value="منزل أو فيلا">منزل أو فيلا</option>
                  <option value="معرض أو مكتب تجاري">معرض أو مكتب تجاري</option>
                  <option value="مستودع أو مصنع">مستودع أو مصنع</option>
                  <option value="عمارة أو مجمع سكني">عمارة أو مجمع سكني</option>
                  <option value="مدرسة أو منشأة">مدرسة أو منشأة</option>
                </select>
              </div>

              <div class="field field-full">
                <label for="prefDate">الوقت المناسب لوصول سيارة الخدمة</label>
                <select id="prefDate" name="preferred_date">
                  <option value="في أقرب وقت متاح اليوم">في أقرب وقت متاح اليوم</option>
                  <option value="الفترة الصباحية (9 صباحاً - 1 ظهراً)">الفترة الصباحية (9 صباحاً - 1 ظهراً)</option>
                  <option value="الفترة المسائية (4 عصراً - 10 مساءً)">الفترة المسائية (4 عصراً - 10 مساءً)</option>
                </select>
              </div>

              <div class="field field-full">
                <label for="d">ملاحظات إضافية عن الموقع أو التركيب <span>(اختياري)</span></label>
                <textarea id="d" name="details" rows="3" placeholder="مثال: رغبة في تمديد مخفي، عدد الأدوار، أو أي استفسار..."></textarea>
              </div>
            </div>

            <button class="btn btn-primary btn-xl btn-block" id="submitBtn" type="submit">
              <span>تأكيد الطلب وإرساله لسيارة الخدمة (حفظ + واتساب)</span>
            </button>

            <div id="st" class="form-status" role="status" aria-live="polite"></div>
          </form>

          <!-- Right/End: Live Cart Summary Box -->
          <aside class="checkout-summary glow-card reveal">
            <div class="summary-head">
              <h3>ملخص سلة سيارة الخدمة</h3>
              <button type="button" class="clear-cart-link" id="clearCartBtn">تفريغ السلة</button>
            </div>

            <div class="checkout-cart-list" id="checkoutCartList">
              <!-- Populated dynamically by JS -->
            </div>

            <div class="checkout-perks">
              <div><span>التوصيل داخل جدة:</span> <strong>عبر سيارة SMART X المجهزة</strong></div>
              <div><span>المعاينة الميدانية:</span> <strong class="teal-text">مجانية</strong></div>
              <div><span>الضمان الرسمي:</span> <strong>سنتان شامل الأجهزة والتركيب</strong></div>
            </div>
          </aside>
        </div>
      </div>
    </section>

  </main>

  <!-- Slide-Out Shopping Cart Drawer -->
  <div class="cart-backdrop" id="cartBackdrop"></div>
  <aside class="cart-drawer" id="cartDrawer" aria-label="سلة طلبات سيارة الخدمة">
    <div class="drawer-head">
      <div>
        <h3>سلة طلبات السيارة</h3>
        <small>توصيل وتركيب مباشر في جميع أحياء جدة</small>
      </div>
      <button type="button" class="drawer-close" id="closeCartBtn" aria-label="إغلاق السلة">×</button>
    </div>

    <div class="drawer-items" id="drawerCartList">
      <!-- Populated by JS -->
    </div>

    <div class="drawer-footer">
      <p class="drawer-note">يتم تأكيد التكلفة النهائية والتركيب معك مباشرة بعد استلام الطلب أو المعاينة المجانية.</p>
      <a href="#checkout" class="btn btn-primary btn-lg btn-block" id="goToCheckoutBtn">إتمام الطلب وتحديد الحي في جدة</a>
    </div>
  </aside>

  <footer class="site-footer">
    <div class="footer-accent"><span></span><span></span></div>
    <div class="wrap footer-inner">
      <a class="brand footer-brand" href="#top">
        <img class="brand-mark" src="assets/logo-mark.png" alt="SMART X">
        <span class="brand-name">SMART <span>X</span></span>
      </a>
      <span class="footer-tagline">متجر SMART X والسيارة المتنقلة لكاميرات المراقبة في جدة</span>
      <div class="footer-right">
        <span dir="ltr"><?= htmlspecialchars($phoneDisplay) ?></span>
        <small>© <?= date('Y') ?> SMART X. جميع الحقوق محفوظة.</small>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
