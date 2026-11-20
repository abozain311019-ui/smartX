<?php
declare(strict_types=1);

$pageTitle = 'المتجر | SMART X كاميرات مراقبة في جدة';
$pageDesc  = 'تصفح جميع كاميرات المراقبة والمنظومات المتكاملة المتوفرة للتوصيل والتركيب الفوري عبر سيارة SMART X في جدة.';

require __DIR__ . '/includes/header.php';

use SmartX\Database;

$products = Database::getCameras();
$activeCat = (string) ($_GET['cat'] ?? 'all');
$allowedCats = ['all', 'bundles', 'outdoor', 'indoor', 'ptz', 'solar', 'anpr'];
if (!in_array($activeCat, $allowedCats, true)) {
    $activeCat = 'all';
}
?>

<main>
  <section class="page-banner">
    <div class="wrap">
      <span class="kicker">متجر SMART X المتنقل</span>
      <h1>الكاميرات والمنظومات <em>المتوفرة في سياراتنا</em></h1>
      <p>أضف الأجهزة التي تحتاجها إلى السلة، وأكمل الطلب في خطوة واحدة لتصلك سيارة الخدمة لباب موقعك في جدة.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="filter-bar" role="tablist" aria-label="تصفية المنتجات">
        <button type="button" class="filter-pill <?= $activeCat === 'all' ? 'active' : '' ?>" data-filter="all">الكل (<?= count($products) ?>)</button>
        <button type="button" class="filter-pill <?= $activeCat === 'bundles' ? 'active' : '' ?>" data-filter="bundles">منظومات متكاملة</button>
        <button type="button" class="filter-pill <?= $activeCat === 'outdoor' ? 'active' : '' ?>" data-filter="outdoor">خارجية 4K</button>
        <button type="button" class="filter-pill <?= $activeCat === 'indoor' ? 'active' : '' ?>" data-filter="indoor">داخلية للأسقف</button>
        <button type="button" class="filter-pill <?= $activeCat === 'ptz' ? 'active' : '' ?>" data-filter="ptz">متحركة 360</button>
        <button type="button" class="filter-pill <?= $activeCat === 'solar' ? 'active' : '' ?>" data-filter="solar">طاقة شمسية</button>
        <button type="button" class="filter-pill <?= $activeCat === 'anpr' ? 'active' : '' ?>" data-filter="anpr">قراءة لوحات</button>
      </div>

      <div class="products-grid-4" id="productGrid">
        <?php foreach ($products as $prod): ?>
          <?php $hidden = ($activeCat !== 'all' && $prod['category'] !== $activeCat); ?>
          <article class="product-card <?= $hidden ? 'is-hidden' : '' ?>" data-category="<?= htmlspecialchars($prod['category']) ?>">
            <div class="product-media">
              <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['name_ar']) ?>" loading="lazy">
              <span class="prod-badge"><?= htmlspecialchars($prod['badge']) ?></span>
            </div>
            <div class="product-body">
              <small class="prod-sku"><?= htmlspecialchars($prod['code']) ?></small>
              <h3><?= htmlspecialchars($prod['name_ar']) ?></h3>
              <p class="prod-desc"><?= htmlspecialchars($prod['description']) ?></p>

              <ul class="prod-specs">
                <li><span>الدقة:</span> <b><?= htmlspecialchars($prod['resolution']) ?></b></li>
                <li><span>الرؤية الليلية:</span> <b><?= htmlspecialchars($prod['night_vision']) ?></b></li>
                <li><span>التغطية:</span> <b><?= htmlspecialchars($prod['fov']) ?></b></li>
              </ul>

              <button
                type="button"
                class="btn btn-primary btn-block add-to-cart-btn"
                data-id="<?= htmlspecialchars($prod['code']) ?>"
                data-name="<?= htmlspecialchars($prod['name_ar']) ?>"
                data-img="<?= htmlspecialchars($prod['image_url']) ?>"
                data-spec="<?= htmlspecialchars($prod['resolution']) ?>"
              >
                أضف لسلة الطلب
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
