<?php
declare(strict_types=1);

$pageTitle = 'عن أعمالنا | SMART X في جدة';
$pageDesc  = 'تعرف على أسلوب عمل SMART X في جدة، فكرة السيارة المتنقلة، ونماذج من حلول التركيب المخفي للفلل والمعارض والمستودعات.';

require __DIR__ . '/includes/header.php';

use SmartX\Database;

$projects = Database::getProjects();
?>

<main>
  <section class="page-banner">
    <div class="wrap">
      <span class="kicker">عن أعمالنا ومنهجيتنا</span>
      <h1>تمديد مخفي، <em>ورؤية صافية تدوم.</em></h1>
      <p>نؤمن بأن تركيب كاميرات المراقبة يجب أن يحمي موقعك دون أن يشوّه جمال الواجهة أو الديكور.</p>
    </div>
  </section>

  <!-- Mobile Van Concept -->
  <section class="section">
    <div class="wrap van-grid">
      <div class="van-copy">
        <span class="kicker">فكرة السيارة المتنقلة</span>
        <h2>بدل ما تشتري الأجهزة وتدور على فني..<br><em>نوصل لك بالاثنين معاً.</em></h2>
        <p>
          سيارات <strong>SMART X</strong> في جدة مجهزة كمعرض وورشة متنقلة متكاملة؛ تحتوي على أحدث الكاميرات وأجهزة التسجيل والكابلات الأصلية ومواسير الحماية. نصل لموقعك، نعاين الزوايا على الطبيعة، وننفذ التركيب والربط على جوالك في زيارة واحدة مرتبة.
        </p>
        <div class="steps-inline">
          <div><strong>01 معاينة مجانية</strong><span>تحديد الزوايا بدون نقاط عمياء</span></div>
          <div><strong>02 تركيب مخفي</strong><span>حماية كاملة للأسلاك ومظهر راقٍ</span></div>
          <div><strong>03 ضمان سنتين</strong><span>دعم ميداني سريع في موقعك بجدة</span></div>
        </div>
      </div>
      <div class="van-img">
        <img src="assets/images/hero-van-jeddah.jpg" alt="سيارة SMART X الميدانية في جدة">
      </div>
    </div>
  </section>

  <!-- Projects Grid -->
  <section class="section section-soft">
    <div class="wrap">
      <div class="section-head">
        <div>
          <span class="kicker">مشاريعنا في جدة</span>
          <h2>حلول مخصصة لكل نوع مبنى</h2>
        </div>
      </div>

      <div class="works-grid-3">
        <?php foreach ($projects as $proj): ?>
          <article class="work-card">
            <div class="work-media">
              <img src="<?= htmlspecialchars($proj['image_url']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>" loading="lazy">
              <span class="work-loc"><?= htmlspecialchars($proj['district']) ?></span>
            </div>
            <div class="work-body">
              <small><?= htmlspecialchars($proj['tag']) ?> • <?= (int) $proj['cameras_installed'] ?> كاميرا</small>
              <h3><?= htmlspecialchars($proj['title']) ?></h3>
              <p><?= htmlspecialchars($proj['summary']) ?></p>
              <div class="work-spec-line"><?= htmlspecialchars($proj['system_spec']) ?></div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
