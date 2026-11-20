<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/includes/Database.php';

use SmartX\Database;

$pdo = Database::connect();
$settings = Database::getSettings();
$adminPin = $settings['admin_pin'] ?? '2026';

$flash = '';

if (isset($_GET['logout'])) {
    unset($_SESSION['smartx_admin']);
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'login') {
        $pin = trim((string) ($_POST['pin'] ?? ''));
        if ($pin === $adminPin) {
            $_SESSION['smartx_admin'] = true;
            header('Location: admin.php');
            exit;
        }
        $flash = 'رمز الدخول غير صحيح. الرمز الافتراضي هو 2026';
    }

    if (!empty($_SESSION['smartx_admin'])) {
        if ($action === 'update_status') {
            $id = (int) ($_POST['lead_id'] ?? 0);
            $status = (string) ($_POST['status'] ?? 'new');
            if (in_array($status, ['new', 'scheduled', 'completed', 'cancelled'], true) && $id > 0) {
                $stmt = $pdo->prepare('UPDATE leads SET status = :s WHERE id = :id');
                $stmt->execute([':s' => $status, ':id' => $id]);
                $flash = 'تم تحديث حالة الطلب بنجاح.';
            }
        } elseif ($action === 'delete_lead') {
            $id = (int) ($_POST['lead_id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare('DELETE FROM leads WHERE id = :id');
                $stmt->execute([':id' => $id]);
                $flash = 'تم حذف الطلب.';
            }
        } elseif ($action === 'save_settings') {
            $fields = ['whatsapp_number', 'phone_display', 'response_time', 'active_vans', 'admin_pin'];
            $stmt = $pdo->prepare('INSERT INTO settings (key, value) VALUES (:k, :v) ON CONFLICT(key) DO UPDATE SET value = :v');
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $val = trim((string) $_POST[$field]);
                    if ($val !== '') {
                        $stmt->execute([':k' => $field, ':v' => $val]);
                    }
                }
            }
            $settings = Database::getSettings();
            $flash = 'تم حفظ إعدادات الموقع ورقم الواتساب بنجاح.';
        }
    }
}

$isLogged = !empty($_SESSION['smartx_admin']);
$leads = $isLogged ? $pdo->query('SELECT * FROM leads ORDER BY id DESC')->fetchAll() : [];

$stats = [
    'total'     => count($leads),
    'new'       => 0,
    'scheduled' => 0,
    'completed' => 0,
];
foreach ($leads as $l) {
    if (isset($stats[$l['status']])) {
        $stats[$l['status']]++;
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl" data-style="editorial">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>لوحة إدارة SMART X | طلبات الخدمة الميدانية بجدة</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800;900&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <div class="wrap header-inner">
      <a class="brand" href="index.php">
        <img class="brand-mark" src="assets/logo-mark.png" alt="SMART X">
        <div class="brand-text">
          <span class="brand-name">SMART <span>X</span></span>
          <small class="brand-tag">لوحة إدارة الطلبات • جدة</small>
        </div>
      </a>
      <div class="header-cta">
        <a class="button button-outline" href="index.php">العودة للموقع الرئيسي</a>
        <?php if ($isLogged): ?>
          <a class="button button-primary" href="admin.php?logout=1">تسجيل خروج</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main class="wrap admin-main">
    <?php if ($flash !== ''): ?>
      <div class="admin-alert"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!$isLogged): ?>
      <section class="admin-login-card surface-card">
        <p class="eyebrow"><span class="eyebrow-dot"></span> إدارة الطلبات الميدانية</p>
        <h1>دخول لوحة إدارة SMART X</h1>
        <p>أدخل رمز الحماية للاطلاع على طلبات الزيارة الميدانية في جدة وتحديث رقم الواتساب الرسمي.</p>
        <form method="post" class="admin-login-form">
          <input type="hidden" name="action" value="login">
          <div class="field">
            <label for="pin">رمز الدخول (الافتراضي: 2026)</label>
            <input id="pin" name="pin" type="password" dir="ltr" placeholder="••••" required autofocus>
          </div>
          <button type="submit" class="button button-primary button-block">دخول لوحة الإدارة</button>
        </form>
      </section>
    <?php else: ?>
      <div class="admin-top-grid">
        <div class="surface-card stat-card">
          <span class="stat-label">إجمالي الطلبات المسجلة</span>
          <strong class="stat-value"><?= $stats['total'] ?></strong>
          <small>جميع أحياء جدة</small>
        </div>
        <div class="surface-card stat-card stat-new">
          <span class="stat-label">طلبات جديدة</span>
          <strong class="stat-value"><?= $stats['new'] ?></strong>
          <small>بانتظار التواصل</small>
        </div>
        <div class="surface-card stat-card stat-scheduled">
          <span class="stat-label">زيارات ميدانية مجدولة</span>
          <strong class="stat-value"><?= $stats['scheduled'] ?></strong>
          <small>تم توجيه سيارة الخدمة</small>
        </div>
        <div class="surface-card stat-card stat-completed">
          <span class="stat-label">مشاريع مكتملة</span>
          <strong class="stat-value"><?= $stats['completed'] ?></strong>
          <small>تم التركيب والتسليم</small>
        </div>
      </div>

      <div class="admin-dashboard-grid">
        <section class="surface-card admin-leads-panel">
          <div class="panel-head">
            <h2>سجل طلبات الزيارة الميدانية وعروض الأسعار</h2>
            <p>تُحفظ جميع الطلبات تلقائياً في قاعدة البيانات لضمان عدم ضياع أي عميل.</p>
          </div>

          <div class="leads-table-wrap">
            <table class="leads-table">
              <thead>
                <tr>
                  <th>رقم الطلب</th>
                  <th>العميل والجوال</th>
                  <th>الحي ونوع المكان</th>
                  <th>النظام المطلوب</th>
                  <th>التقدير والموعد</th>
                  <th>الحالة</th>
                  <th>حذف</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($leads as $lead): ?>
                  <tr>
                    <td><span class="code-pill"><?= htmlspecialchars($lead['tracking_code']) ?></span></td>
                    <td>
                      <strong><?= htmlspecialchars($lead['name']) ?></strong>
                      <div class="td-sub" dir="ltr">
                        <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/\D+/', '', $lead['phone']) ?? '') ?>" target="_blank" rel="noopener">
                          <?= htmlspecialchars($lead['phone']) ?>
                        </a>
                      </div>
                    </td>
                    <td>
                      <strong><?= htmlspecialchars($lead['district']) ?></strong>
                      <div class="td-sub"><?= htmlspecialchars($lead['place_type']) ?></div>
                    </td>
                    <td>
                      <strong><?= htmlspecialchars($lead['cameras_count']) ?></strong>
                      <div class="td-sub"><?= htmlspecialchars($lead['resolution']) ?></div>
                      <?php if (!empty($lead['details'])): ?>
                        <div class="td-note"><?= htmlspecialchars($lead['details']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <strong class="price-highlight"><?= htmlspecialchars($lead['estimated_price']) ?></strong>
                      <div class="td-sub"><?= htmlspecialchars($lead['preferred_date']) ?></div>
                      <div class="td-sub"><?= htmlspecialchars($lead['created_at']) ?></div>
                    </td>
                    <td>
                      <form method="post">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="lead_id" value="<?= (int) $lead['id'] ?>">
                        <select name="status" onchange="this.form.submit()" class="status-select">
                          <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>طلب جديد</option>
                          <option value="scheduled" <?= $lead['status'] === 'scheduled' ? 'selected' : '' ?>>تم جدولة الزيارة</option>
                          <option value="completed" <?= $lead['status'] === 'completed' ? 'selected' : '' ?>>تم التركيب</option>
                          <option value="cancelled" <?= $lead['status'] === 'cancelled' ? 'selected' : '' ?>>ملغي</option>
                        </select>
                      </form>
                    </td>
                    <td>
                      <form method="post" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟');">
                        <input type="hidden" name="action" value="delete_lead">
                        <input type="hidden" name="lead_id" value="<?= (int) $lead['id'] ?>">
                        <button type="submit" class="btn-delete" title="حذف">حذف</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>

        <aside class="surface-card admin-settings-panel">
          <h2>إعدادات التواصل والواتساب</h2>
          <p>تعديل رقم الواتساب هنا يفعّل الإرسال المباشر في جميع أزرار الموقع.</p>
          <form method="post" class="settings-form">
            <input type="hidden" name="action" value="save_settings">
            <div class="field">
              <label>رقم واتساب الشركة (بالمفتاح الدولي بدون +)</label>
              <input name="whatsapp_number" dir="ltr" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>" placeholder="9665XXXXXXXX" required>
            </div>
            <div class="field">
              <label>رقم الجوال المعروض للاتصال</label>
              <input name="phone_display" dir="ltr" value="<?= htmlspecialchars($settings['phone_display'] ?? '') ?>" placeholder="050 000 0000" required>
            </div>
            <div class="field">
              <label>متوسط وقت الوصول في جدة</label>
              <input name="response_time" value="<?= htmlspecialchars($settings['response_time'] ?? '') ?>" required>
            </div>
            <div class="field">
              <label>رمز الدخول للوحة الإدارة</label>
              <input name="admin_pin" dir="ltr" value="<?= htmlspecialchars($settings['admin_pin'] ?? '2026') ?>" required>
            </div>
            <button type="submit" class="button button-primary button-block">حفظ الإعدادات</button>
          </form>
        </aside>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
