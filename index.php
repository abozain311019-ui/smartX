<?php
declare(strict_types=1);

$pageTitle = 'SMART X | كاميرات مراقبة وسيارة خدمة متنقلة في جدة';
$pageDesc  = 'متجر SMART X المتنقل في جدة: اختر كاميراتك أو منظومتك وتصلك سيارة الخدمة للتوصيل والتركيب المخفي في نفس اليوم.';

require __DIR__ . '/includes/header.php';

use SmartX\Database;

$allProducts = Database::getCameras();
$featuredProducts = array_slice($allProducts, 0, 4);
$projects = Database::getProjects();

$homeCategories = [
    ['id' => 'bundles', 'title' => 'منظومات متكاملة', 'sub' => 'كاميرات + جهاز تسجيل + تركيب', 'img' => 'assets/images/hero-van-jeddah.jpg'],
    ['id' => 'outdoor', 'title' => 'كاميرات خارجية 4K', 'sub' => 'مقاومة لحرارة ورطوبة جدة', 'img' => 'assets/images/cam-bullet.jpg'],
    ['id' => 'indoor',  'title' => 'كاميرات داخلية للأسقف', 'sub' => 'مدمجة بهدوء مع الديكور', 'img' => 'assets/images/cam-dome.jpg'],
    ['id' => 'solar',   'title' => 'طاقة شمسية ومتحركة', 'sub' => 'بدون أسلاك أو بتغطية 360', 'img' => 'assets/images/cam-solar.jpg'],
];
?>

<main>
  <!-- 1. Clean Hero -->
  <section class="hero">
    <div class="wrap hero-grid">
      <div class="hero-copy">
        <span class="eyebrow"><i class="eyebrow-dot"></i> المتجر المتنقل الأول في جدة</span>
        <h1>متجر الكاميرات كامل<br><em>يوصلك بسيارة الخدمة.</em></h1>
        <p class="hero-lede">
          اطلب كاميراتك أو منظومتك من متجر <strong>SMART X</strong>، وسيارتنا المجهزة توصلك لأي حي في جدة للمعاينة والتركيب المخفي والربط على جوالك في نفس اليوم.
        </p>
        <div class="hero-cta-row">
          <a href="shop.php" class="btn btn-primary btn-lg">تصفح المتجر</a>
          <a href="checkout.php" class="btn btn-outline btn-lg">اطلب سيارة الخدمة</a>
        </div>
        <div class="hero-points">
          <span>توصيل وتركيب في جدة</span>
          <span>تمديد مخفي ونظيف</span>
          <span>ضمان شامل سنتين</span>
        </div>
      </div>

      <figure class="hero-photo">
        <img src="assets/images/hero-van-jeddah.jpg" alt="سيارة SMART X المتنقلة أمام فيلا حديثة في جدة" width="1600" height="900" fetchpriority="high">
        <figcaption class="hero-caption">سيارة مجهزة بجميع الأجهزة والكابلات لتخديم جميع أحياء جدة</figcaption>
      </figure>
    </div>
  </section>

  <!-- 2. Four Clean Store Categories -->
  <section class="section">
    <div class="wrap">
      <div class="section-head">
        <div>
          <span class="kicker">أقسام المتجر</span>
          <h2>اختر حسب احتياج موقعك</h2>
        </div>
        <a href="shop.php" class="text-link">عرض جميع الأقسام ←</a>
      </div>

      <div class="cat-grid-4">
        <?php foreach ($homeCategories as $cat): ?>
          <a href="shop.php?cat=<?= urlencode($cat['id']) ?>" class="cat-card">
            <div class="cat-img">
              <img src="<?= htmlspecialchars($cat['img']) ?>" alt="<?= htmlspecialchars($cat['title']) ?>" loading="lazy">
            </div>
            <div class="cat-body">
              <h3><?= htmlspecialchars($cat['title']) ?></h3>
              <p><?= htmlspecialchars($cat['sub']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 3. Featured 4 Products Only (Clean, Uncluttered) -->
  <section class="section section-soft">
    <div class="wrap">
      <div class="section-head">
        <div>
          <span class="kicker">الأكثر طلباً في جدة</span>
          <h2>مختارات من متجر SMART X</h2>
        </div>
        <a href="shop.php" class="btn btn-outline">الانتقال إلى المتجر بالكامل</a>
      </div>

      <div class="products-grid-4">
        <?php foreach ($featuredProducts as $prod): ?>
          <article class="product-card">
            <div class="product-media">
              <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['name_ar']) ?>" loading="lazy">
              <span class="prod-badge"><?= htmlspecialchars($prod['badge']) ?></span>
            </div>
            <div class="product-body">
              <h3><?= htmlspecialchars($prod['name_ar']) ?></h3>
              <p><?= htmlspecialchars($prod['resolution']) ?></p>
              <button
                type="button"
                class="btn btn-primary btn-block add-to-cart-btn"
                data-id="<?= htmlspecialchars($prod['code']) ?>"
                data-name="<?= htmlspecialchars($prod['name_ar']) ?>"
                data-img="<?= htmlspecialchars($prod['image_url']) ?>"
                data-spec="<?= htmlspecialchars($prod['resolution']) ?>"
              >
                أضف للسلة
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 4. Brief Works Glimpse -->
  <section class="section">
    <div class="wrap">
      <div class="section-head">
        <div>
          <span class="kicker">عن أعمالنا</span>
          <h2>نماذج من تنفيذنا في جدة</h2>
        </div>
        <a href="works.php" class="text-link">شاهد تفاصيل أعمالنا ←</a>
      </div>

      <div class="works-grid-3">
        <?php foreach ($projects as $proj): ?>
          <article class="work-card">
            <div class="work-media">
              <img src="<?= htmlspecialchars($proj['image_url']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>" loading="lazy">
              <span class="work-loc"><?= htmlspecialchars($proj['district']) ?></span>
            </div>
            <div class="work-body">
              <small><?= htmlspecialchars($proj['tag']) ?></small>
              <h3><?= htmlspecialchars($proj['title']) ?></h3>
              <p><?= htmlspecialchars($proj['summary']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 5. Clean Dual CTA: Store Order vs Direct WhatsApp -->
  <section class="section section-soft">
    <div class="wrap">
      <div class="cta-banner">
        <div>
          <h2>جاهز نجهز موقعك في جدة؟</h2>
          <p>يمكنك إتمام طلبك مباشرة عبر المتجر لتصلك سيارة الخدمة، أو التواصل معنا مباشرة عبر واتساب للاستفسار السريع.</p>
        </div>
        <div class="cta-actions">
          <a href="checkout.php" class="btn btn-primary btn-lg">إتمام طلب عبر المتجر</a>
          <a
            href="https://wa.me/<?= htmlspecialchars($waNumber) ?>?text=<?= rawurlencode('مرحباً SMART X، أرغب في استشارة حول كاميرات المراقبة في جدة.') ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-whatsapp btn-lg"
          >
            تواصل مباشر عبر واتساب
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
