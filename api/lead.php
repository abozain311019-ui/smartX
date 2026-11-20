<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/Database.php';

use SmartX\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'طريقة الطلب غير مسموحة'], JSON_UNESCAPED_UNICODE);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode((string) $raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

$name            = trim((string) ($data['name'] ?? ''));
$phone           = trim((string) ($data['phone'] ?? ''));
$district        = trim((string) ($data['district'] ?? 'وسط جدة'));
$placeType       = trim((string) ($data['place'] ?? 'منزل أو فيلا'));
$camerasCount    = trim((string) ($data['cameras'] ?? '4 كاميرات'));
$resolution      = trim((string) ($data['resolution'] ?? 'دقة فائقة الوضوح 4K'));
$preferredDate   = trim((string) ($data['preferred_date'] ?? 'في أقرب وقت متاح'));
$estimatedPrice  = trim((string) ($data['estimated_price'] ?? 'يحدد بعد المعاينة المجانية'));
$details         = trim((string) ($data['details'] ?? ''));

$digits = preg_replace('/\D+/', '', $phone) ?? '';

if (mb_strlen($name) < 2 || strlen($digits) < 9) {
    http_response_code(422);
    echo json_encode([
        'ok'      => false,
        'message' => 'فضلاً اكتب اسمك ورقم جوال صحيح (9 أرقام على الأقل) لنتمكن من التواصل معك.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = Database::connect();
    $settings = Database::getSettings();

    $trackingCode = 'SX-' . random_int(1000, 9999);
    $stmt = $pdo->prepare(<<<'SQL'
        INSERT INTO leads (
            tracking_code, name, phone, district, place_type, cameras_count,
            resolution, preferred_date, estimated_price, details, status, created_at
        ) VALUES (
            :tracking_code, :name, :phone, :district, :place_type, :cameras_count,
            :resolution, :preferred_date, :estimated_price, :details, 'new', :created_at
        )
    SQL);

    $stmt->execute([
        ':tracking_code'   => $trackingCode,
        ':name'            => $name,
        ':phone'           => $phone,
        ':district'        => $district ?: 'جدة',
        ':place_type'      => $placeType,
        ':cameras_count'   => $camerasCount,
        ':resolution'      => $resolution,
        ':preferred_date'  => $preferredDate,
        ':estimated_price' => $estimatedPrice,
        ':details'         => $details,
        ':created_at'      => date('Y-m-d H:i:s'),
    ]);

    $waNumber = preg_replace('/\D+/', '', (string) ($settings['whatsapp_number'] ?? '')) ?? '';
    $waText = implode("\n", [
        "طلب زيارة ميدانية جديد — SMART X جدة",
        "رقم الطلب: {$trackingCode}",
        "--------------------",
        "الاسم: {$name}",
        "الجوال: {$phone}",
        "الحي في جدة: {$district}",
        "نوع المكان: {$placeType}",
        "عدد الكاميرات: {$camerasCount}",
        "الدقة المطلوبة: {$resolution}",
        "الموعد المفضل: {$preferredDate}",
        "التقدير المبدئي: {$estimatedPrice}",
        "تفاصيل إضافية: " . ($details !== '' ? $details : 'لا توجد'),
    ]);

    $waUrl = $waNumber !== ''
        ? 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText)
        : '';

    echo json_encode([
        'ok'            => true,
        'tracking_code' => $trackingCode,
        'message'       => "تم تسجيل طلبك بنجاح برقم ({$trackingCode})، وسيتواصل معك فريق الخدمة الميدانية في جدة.",
        'whatsapp_url'  => $waUrl,
        'whatsapp_text' => $waText,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok'      => false,
        'message' => 'حدث خطأ أثناء حفظ الطلب، يرجى المحاولة مرة أخرى.',
    ], JSON_UNESCAPED_UNICODE);
}
