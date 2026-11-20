  <!-- Slide-Out Store Cart Drawer -->
  <div class="cart-backdrop" id="cartBackdrop"></div>
  <aside class="cart-drawer" id="cartDrawer" aria-label="سلة المتجر">
    <div class="drawer-head">
      <div>
        <h3>سلة المتجر</h3>
        <small>توصيل وتركيب عبر سيارة SMART X في جدة</small>
      </div>
      <button type="button" class="drawer-close" id="closeCartBtn" aria-label="إغلاق السلة">×</button>
    </div>

    <div class="drawer-items" id="drawerCartList"></div>

    <div class="drawer-footer">
      <p class="drawer-note">الطلب يُسجّل مباشرة في نظام المتجر، وتصلك سيارة الخدمة للمعاينة والتركيب.</p>
      <a href="checkout.php" class="btn btn-primary btn-lg btn-block">إتمام الطلب في المتجر</a>
    </div>
  </aside>

  <!-- Dedicated Floating WhatsApp Button (Completely Separate from Store Backend) -->
  <a
    href="https://wa.me/<?= htmlspecialchars($waNumber) ?>?text=<?= rawurlencode('مرحباً SMART X، أحتاج استشارة سريعة لكاميرات مراقبة في جدة.') ?>"
    target="_blank"
    rel="noopener noreferrer"
    class="wa-float"
    aria-label="تواصل مباشر عبر واتساب"
  >
    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.52 3.48A11.86 11.86 0 0 0 12.06 0C5.5 0 .16 5.34.16 11.9c0 2.1.55 4.15 1.6 5.96L0 24l6.32-1.66a11.86 11.86 0 0 0 5.74 1.46h.01c6.56 0 11.9-5.34 11.9-11.9 0-3.18-1.24-6.17-3.45-8.42zm-8.46 18.3h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.75.98 1-3.65-.24-.38a9.84 9.84 0 0 1-1.51-5.26c0-5.44 4.43-9.87 9.89-9.87 2.64 0 5.12 1.03 6.98 2.9a9.82 9.82 0 0 1 2.89 6.98c0 5.45-4.43 9.89-9.86 9.89zm5.42-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.21 5.08 4.5.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.42-.07-.12-.27-.2-.57-.35z"/></svg>
    <span>واتساب</span>
  </a>

  <footer class="site-footer">
    <div class="footer-accent"><span></span><span></span></div>
    <div class="wrap footer-inner">
      <a class="brand footer-brand" href="index.php">
        <img class="brand-mark" src="assets/logo-mark.png" alt="SMART X">
        <span class="brand-name">SMART <span>X</span></span>
      </a>

      <div class="footer-links">
        <a href="index.php">الرئيسية</a>
        <a href="shop.php">المتجر</a>
        <a href="works.php">أعمالنا</a>
        <a href="checkout.php">إتمام الطلب</a>
        <a href="admin.php">لوحة الإدارة</a>
      </div>

      <div class="footer-meta">
        <span dir="ltr"><?= htmlspecialchars($phoneDisplay) ?></span>
        <small>© <?= date('Y') ?> SMART X — جدة</small>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
