<?php
declare(strict_types=1);

$pageTitle = 'إتمام الطلب والتوصيل | متجر SMART X جدة';
$pageDesc  = 'أكمل طلبك من متجر SMART X وحدد حيّك في جدة لتصلك سيارة الخدمة المتنقلة للتوصيل والتركيب.';

require __DIR__ . '/includes/header.php';
?>

<main>
  <section class="page-banner">
    <div class="wrap">
      <span class="kicker">إتمام الطلب — نظام المتجر</span>
      <h1>الشحن والتوصيل <em>عبر سيارة SMART X</em></h1>
      <p>أكمل بياناتك ليتم تسجيل طلبك مباشرة في نظامنا وإصدار رقم متابعة رسمي لتوجيه أقرب سيارة خدمة في جدة.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap checkout-grid">
      <!-- Store Backend Checkout Form (No forced WhatsApp redirect) -->
      <div class="checkout-card">
        <form id="storeCheckoutForm" novalidate>
          <div class="form-head">
            <h2>1. طريقة الخدمة والتوصيل في جدة</h2>
          </div>

          <div class="delivery-modes">
            <label class="delivery-option active">
              <input type="radio" name="service_mode" value="توصيل وتركيب فوري عبر سيارة SMART X المتنقلة" checked>
              <div>
                <strong>توصيل وتركيب بالسيارة المتنقلة</strong>
                <small>تصلك السيارة بالأجهزة المختارة للتركيب في نفس اليوم</small>
              </div>
            </label>

            <label class="delivery-option">
              <input type="radio" name="service_mode" value="زيارة معاينة مجانية أولاً قبل التوريد">
              <div>
                <strong>معاينة مجانية أولاً</strong>
                <small>نعاين موقعك ونؤكد معك العدد والأنسب قبل التركيب</small>
              </div>
            </label>
          </div>

          <div class="form-head" style="margin-top: 26px;">
            <h2>2. بيانات العميل والموقع</h2>
          </div>

          <div class="form-fields">
            <div class="field">
              <label for="n">الاسم الكريم *</label>
              <input id="n" name="name" autocomplete="name" placeholder="اكتب اسمك الكريم" required>
            </div>

            <div class="field">
              <label for="p">رقم الجوال *</label>
              <input id="p" name="phone" type="tel" inputmode="tel" autocomplete="tel" dir="ltr" placeholder="05X XXX XXXX" required>
            </div>

            <div class="field">
              <label for="district">الحي في جدة *</label>
              <input id="district" name="district" placeholder="مثال: الشاطئ، الروضة، أبحر، السلامة..." required>
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
              <label for="prefDate">الوقت المناسب لوصول السيارة</label>
              <select id="prefDate" name="preferred_date">
                <option value="في أقرب وقت متاح اليوم">في أقرب وقت متاح اليوم</option>
                <option value="الفترة الصباحية (9 صباحاً - 1 ظهراً)">الفترة الصباحية (9 صباحاً - 1 ظهراً)</option>
                <option value="الفترة المسائية (4 عصراً - 10 مساءً)">الفترة المسائية (4 عصراً - 10 مساءً)</option>
              </select>
            </div>

            <div class="field field-full">
              <label for="d">ملاحظات إضافية <span>(اختياري)</span></label>
              <textarea id="d" name="details" rows="3" placeholder="أي تفاصيل تود إضافتها لفريق التركيب..."></textarea>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitBtn">
            تأكيد الطلب وتسجيله في النظام
          </button>

          <div id="orderStatus" class="form-status" role="status" aria-live="polite"></div>
        </form>

        <!-- Order Confirmation Receipt (Shown after backend save — Pure Store Experience) -->
        <div id="orderReceipt" class="order-receipt" style="display:none;">
          <div class="receipt-icon">✓</div>
          <h2>تم استلام طلبك بنجاح!</h2>
          <p>تم تسجيل طلبك في نظام متجر SMART X وسيقوم فريقنا في جدة بالتواصل معك لتأكيد موعد وصول السيارة.</p>
          <div class="receipt-code-box">
            <span>رقم متابعة الطلب</span>
            <strong id="receiptCode">SX-0000</strong>
          </div>
          <div class="receipt-actions">
            <a href="shop.php" class="btn btn-outline">العودة إلى المتجر</a>
            <a href="index.php" class="btn btn-primary">الصفحة الرئيسية</a>
          </div>
        </div>
      </div>

      <!-- Summary Sidebar -->
      <aside class="checkout-summary">
        <div class="summary-head">
          <h3>ملخص سلة المتجر</h3>
          <button type="button" class="clear-cart-link" id="clearCartBtn">تفريغ السلة</button>
        </div>

        <div class="checkout-cart-list" id="checkoutCartList"></div>

        <div class="checkout-perks">
          <div><span>التوصيل داخل جدة:</span> <strong>عبر سيارة SMART X</strong></div>
          <div><span>المعاينة الميدانية:</span> <strong class="teal-text">مجانية</strong></div>
          <div><span>الضمان:</span> <strong>سنتان شامل الأجهزة والتركيب</strong></div>
        </div>
      </aside>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
