/**
 * SMART X — Clean Multi-Page Storefront & Backend Checkout Engine
 * Store Orders -> Pure PHP + SQLite Backend (No forced WhatsApp)
 * WhatsApp -> Independent Direct Contact Channels
 */

document.addEventListener("DOMContentLoaded", () => {
  // 1. Mobile Navigation
  const menuToggle = document.getElementById("menuToggle");
  const siteNav = document.getElementById("siteNav");
  if (menuToggle && siteNav) {
    menuToggle.addEventListener("click", () => {
      const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
      menuToggle.setAttribute("aria-expanded", String(!isOpen));
      siteNav.classList.toggle("is-open", !isOpen);
    });
  }

  // 2. Shop Category Filter (on shop.php)
  const filterPills = document.querySelectorAll(".filter-pill");
  const productCards = document.querySelectorAll(".product-card[data-category]");
  filterPills.forEach((pill) => {
    pill.addEventListener("click", () => {
      filterPills.forEach((p) => p.classList.remove("active"));
      pill.classList.add("active");
      const cat = pill.getAttribute("data-filter") || "all";
      productCards.forEach((card) => {
        const show = cat === "all" || card.getAttribute("data-category") === cat;
        card.classList.toggle("is-hidden", !show);
      });
    });
  });

  // 3. Persistent Store Cart (shared across index.php, shop.php, works.php, checkout.php)
  let cart = [];
  try {
    cart = JSON.parse(localStorage.getItem("smartx_store_cart") || "[]");
    if (!Array.isArray(cart)) cart = [];
  } catch {
    cart = [];
  }

  const cartCountEl = document.getElementById("cartCount");
  const drawerCartList = document.getElementById("drawerCartList");
  const checkoutCartList = document.getElementById("checkoutCartList");
  const cartDrawer = document.getElementById("cartDrawer");
  const cartBackdrop = document.getElementById("cartBackdrop");

  function openCart() {
    if (cartDrawer) cartDrawer.classList.add("open");
    if (cartBackdrop) cartBackdrop.classList.add("open");
  }
  function closeCart() {
    if (cartDrawer) cartDrawer.classList.remove("open");
    if (cartBackdrop) cartBackdrop.classList.remove("open");
  }
  function saveCart() {
    localStorage.setItem("smartx_store_cart", JSON.stringify(cart));
  }

  function renderCart() {
    const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    if (cartCountEl) cartCountEl.textContent = String(totalQty);

    const html = cart.length === 0
      ? `<div class="cart-empty-msg">سلة المتجر فارغة حالياً.<br>يمكنك إضافة منتجات من المتجر أو طلب معاينة مجانية مباشرة.</div>`
      : cart.map((item) => `
          <div class="cart-item-row">
            <img src="${item.img}" alt="${item.name}">
            <div class="cart-item-info">
              <strong>${item.name}</strong>
              <small>${item.spec}</small>
            </div>
            <div class="qty-controls">
              <button type="button" class="qty-btn" data-qty-act="dec" data-id="${item.id}">-</button>
              <span class="qty-val">${item.qty}</span>
              <button type="button" class="qty-btn" data-qty-act="inc" data-id="${item.id}">+</button>
            </div>
          </div>
        `).join("");

    if (drawerCartList) drawerCartList.innerHTML = html;
    if (checkoutCartList) checkoutCartList.innerHTML = html;

    document.querySelectorAll(".qty-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const act = btn.getAttribute("data-qty-act");
        const item = cart.find((i) => i.id === id);
        if (!item) return;
        if (act === "inc") item.qty += 1;
        if (act === "dec") item.qty -= 1;
        cart = cart.filter((i) => i.qty > 0);
        saveCart();
        renderCart();
      });
    });
  }

  document.querySelectorAll(".add-to-cart-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id") || "";
      const name = btn.getAttribute("data-name") || "";
      const img = btn.getAttribute("data-img") || "";
      const spec = btn.getAttribute("data-spec") || "";

      const existing = cart.find((i) => i.id === id);
      if (existing) existing.qty += 1;
      else cart.push({ id, name, img, spec, qty: 1 });

      saveCart();
      renderCart();

      const old = btn.textContent;
      btn.textContent = "تمت الإضافة للسلة ✓";
      setTimeout(() => { btn.textContent = old; }, 1000);
      openCart();
    });
  });

  const cartTrigger = document.getElementById("cartTrigger");
  const closeCartBtn = document.getElementById("closeCartBtn");
  const clearCartBtn = document.getElementById("clearCartBtn");

  if (cartTrigger) cartTrigger.addEventListener("click", openCart);
  if (closeCartBtn) closeCartBtn.addEventListener("click", closeCart);
  if (cartBackdrop) cartBackdrop.addEventListener("click", closeCart);
  if (clearCartBtn) {
    clearCartBtn.addEventListener("click", () => {
      cart = [];
      saveCart();
      renderCart();
    });
  }

  renderCart();

  // 4. Delivery Radio Visual State
  document.querySelectorAll(".delivery-option input").forEach((radio) => {
    radio.addEventListener("change", () => {
      document.querySelectorAll(".delivery-option").forEach((opt) => {
        opt.classList.toggle("active", opt.contains(radio));
      });
    });
  });

  // 5. Pure Store Backend Checkout (Saves directly to SQLite & shows Order Receipt — NO WhatsApp redirect)
  const checkoutForm = document.getElementById("storeCheckoutForm");
  const orderStatus = document.getElementById("orderStatus");
  const orderReceipt = document.getElementById("orderReceipt");
  const receiptCode = document.getElementById("receiptCode");
  const submitBtn = document.getElementById("submitBtn");

  if (checkoutForm) {
    checkoutForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const name = (document.getElementById("n")?.value || "").trim();
      const phone = (document.getElementById("p")?.value || "").trim();
      const district = (document.getElementById("district")?.value || "").trim();
      const place = document.getElementById("t")?.value || "منزل أو فيلا";
      const preferredDate = document.getElementById("prefDate")?.value || "في أقرب وقت متاح اليوم";
      const serviceMode = document.querySelector('input[name="service_mode"]:checked')?.value || "توصيل وتركيب بالسيارة المتنقلة";
      const notes = (document.getElementById("d")?.value || "").trim();

      if (name.length < 2 || phone.replace(/\D/g, "").length < 9 || district.length < 2) {
        orderStatus.className = "form-status e";
        orderStatus.textContent = "فضلاً أكمل الاسم ورقم الجوال والحي في جدة لتسجيل الطلب.";
        return;
      }

      const itemsSummary = cart.length > 0
        ? cart.map((i) => `${i.name} × ${i.qty}`).join(" | ")
        : "طلب زيارة معاينة مجانية بدون منتجات محددة";

      const fullDetails = [
        `طريقة الخدمة: ${serviceMode}`,
        `محتويات السلة: ${itemsSummary}`,
        notes ? `ملاحظات العميل: ${notes}` : ""
      ].filter(Boolean).join(" — ");

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = "جاري حفظ الطلب في نظام المتجر...";
      }

      try {
        const res = await fetch("api/lead.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            name,
            phone,
            district,
            place,
            cameras: cart.length > 0 ? `${cart.reduce((s, i) => s + i.qty, 0)} منتج من المتجر` : "زيارة معاينة مجانية",
            resolution: serviceMode,
            preferred_date: preferredDate,
            estimated_price: "يؤكد بعد المعاينة والتركيب",
            details: fullDetails
          })
        });

        const result = await res.json();
        if (!res.ok || !result.ok) {
          throw new Error(result.message || "تعذر تسجيل الطلب حالياً.");
        }

        // Clear cart and display clean store order receipt
        cart = [];
        saveCart();
        renderCart();

        checkoutForm.style.display = "none";
        if (receiptCode) receiptCode.textContent = result.tracking_code || "SX-OK";
        if (orderReceipt) orderReceipt.style.display = "block";
      } catch (err) {
        orderStatus.className = "form-status e";
        orderStatus.textContent = err.message || "حدث خطأ في الاتصال، حاول مرة أخرى.";
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = "تأكيد الطلب وتسجيله في النظام";
        }
      }
    });
  }
});
