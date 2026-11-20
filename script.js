/**
 * SMART X — Mobile Showroom & Storefront Engine (Jeddah)
 * Zero-Bootstrap • Persistent Van Cart • Instant Checkout + PHP SQLite + WhatsApp
 */

document.addEventListener("DOMContentLoaded", () => {
  // 1. Scroll Progress Bar
  const scrollProgress = document.getElementById("scrollProgress");
  window.addEventListener("scroll", () => {
    if (!scrollProgress) return;
    const h = document.documentElement;
    const scrolled = (h.scrollTop / (h.scrollHeight - h.clientHeight)) * 100;
    scrollProgress.style.width = `${Math.min(100, Math.max(0, scrolled))}%`;
  }, { passive: true });

  // 2. Smooth Scroll Reveal
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll(".reveal").forEach((el) => revealObserver.observe(el));

  // 3. Mobile Navigation Menu
  const menuToggle = document.getElementById("menuToggle");
  const siteNav = document.getElementById("siteNav");
  function closeMenu() {
    if (!menuToggle || !siteNav) return;
    menuToggle.setAttribute("aria-expanded", "false");
    siteNav.classList.remove("is-open");
  }
  if (menuToggle && siteNav) {
    menuToggle.addEventListener("click", () => {
      const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
      menuToggle.setAttribute("aria-expanded", String(!isOpen));
      siteNav.classList.toggle("is-open", !isOpen);
    });
    siteNav.querySelectorAll("a").forEach((a) => a.addEventListener("click", closeMenu));
  }

  // 4. Category Jump Cards & Store Product Filter
  const filterPills = document.querySelectorAll(".filter-pill");
  const productCards = document.querySelectorAll(".product-card");
  const storeSection = document.getElementById("store");

  function applyFilter(category) {
    filterPills.forEach((p) => {
      p.classList.toggle("active", p.getAttribute("data-filter") === category);
    });
    productCards.forEach((card) => {
      const show = category === "all" || card.getAttribute("data-category") === category;
      card.classList.toggle("is-hidden", !show);
    });
  }

  filterPills.forEach((pill) => {
    pill.addEventListener("click", () => applyFilter(pill.getAttribute("data-filter") || "all"));
  });

  document.querySelectorAll("[data-jump-category]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const cat = btn.getAttribute("data-jump-category") || "all";
      applyFilter(cat);
      if (storeSection) storeSection.scrollIntoView({ behavior: "smooth" });
    });
  });

  // 5. Persistent Mobile Van Shopping Cart
  let cart = [];
  try {
    cart = JSON.parse(localStorage.getItem("smartx_van_cart") || "[]");
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
    localStorage.setItem("smartx_van_cart", JSON.stringify(cart));
  }

  function renderCart() {
    const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    if (cartCountEl) cartCountEl.textContent = String(totalQty);

    const buildHTML = () => {
      if (cart.length === 0) {
        return `<div class="cart-empty-msg">سلة سيارة الخدمة فارغة حالياً.<br>يمكنك إضافة منتجات من المتجر أو طلب زيارة معاينة مجانية مباشرة.</div>`;
      }
      return cart.map((item) => `
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
    };

    const html = buildHTML();
    if (drawerCartList) drawerCartList.innerHTML = html;
    if (checkoutCartList) checkoutCartList.innerHTML = html;

    document.querySelectorAll(".qty-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const act = btn.getAttribute("data-qty-act");
        const target = cart.find((i) => i.id === id);
        if (!target) return;
        if (act === "inc") target.qty += 1;
        if (act === "dec") target.qty -= 1;
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
      if (existing) {
        existing.qty += 1;
      } else {
        cart.push({ id, name, img, spec, qty: 1 });
      }
      saveCart();
      renderCart();

      const originalText = btn.innerHTML;
      btn.innerHTML = "<span>تمت الإضافة لسلة السيارة ✓</span>";
      setTimeout(() => { btn.innerHTML = originalText; }, 1100);
      openCart();
    });
  });

  const cartTrigger = document.getElementById("cartTrigger");
  const closeCartBtn = document.getElementById("closeCartBtn");
  const goToCheckoutBtn = document.getElementById("goToCheckoutBtn");
  const clearCartBtn = document.getElementById("clearCartBtn");

  if (cartTrigger) cartTrigger.addEventListener("click", openCart);
  if (closeCartBtn) closeCartBtn.addEventListener("click", closeCart);
  if (cartBackdrop) cartBackdrop.addEventListener("click", closeCart);
  if (goToCheckoutBtn) goToCheckoutBtn.addEventListener("click", closeCart);
  if (clearCartBtn) {
    clearCartBtn.addEventListener("click", () => {
      cart = [];
      saveCart();
      renderCart();
    });
  }

  renderCart();

  // 6. Delivery Mode Radio Highlighting
  document.querySelectorAll(".delivery-option input").forEach((radio) => {
    radio.addEventListener("change", () => {
      document.querySelectorAll(".delivery-option").forEach((opt) => {
        opt.classList.toggle("active", opt.contains(radio));
      });
    });
  });

  // 7. Interactive 4K Clarity Slider
  const compRange = document.getElementById("compRange");
  const compBeforeLayer = document.getElementById("compBeforeLayer");
  const compHandle = document.getElementById("compHandle");
  if (compRange && compBeforeLayer && compHandle) {
    compRange.addEventListener("input", (e) => {
      const pct = `${e.target.value}%`;
      compBeforeLayer.style.width = pct;
      compHandle.style.left = pct;
    });
  }

  // 8. Jeddah Coverage Zones
  const zoneTabs = document.querySelectorAll(".zone-tab");
  const zonePanels = document.querySelectorAll(".zone-panel");
  const districtInput = document.getElementById("district");
  const checkoutSection = document.getElementById("checkout");

  zoneTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      zoneTabs.forEach((t) => t.classList.remove("active"));
      zonePanels.forEach((p) => p.classList.remove("active"));
      tab.classList.add("active");
      const panel = document.getElementById(`zone-${tab.getAttribute("data-zone")}`);
      if (panel) panel.classList.add("active");
    });
  });

  document.querySelectorAll(".district-chip").forEach((chip) => {
    chip.addEventListener("click", () => {
      if (districtInput) districtInput.value = chip.getAttribute("data-district") || "";
      if (checkoutSection) checkoutSection.scrollIntoView({ behavior: "smooth" });
    });
  });

  // 9. Checkout Submission -> PHP SQLite + WhatsApp with Full Cart Details
  const quoteForm = document.getElementById("qf");
  const statusBox = document.getElementById("st");
  const submitBtn = document.getElementById("submitBtn");

  if (quoteForm) {
    quoteForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const name = (document.getElementById("n")?.value || "").trim();
      const phone = (document.getElementById("p")?.value || "").trim();
      const district = (districtInput?.value || "جدة").trim();
      const place = document.getElementById("t")?.value || "منزل أو فيلا";
      const preferredDate = document.getElementById("prefDate")?.value || "في أقرب وقت متاح اليوم";
      const serviceMode = document.querySelector('input[name="service_mode"]:checked')?.value || "توصيل وتركيب عبر السيارة المتنقلة";
      const userNotes = (document.getElementById("d")?.value || "").trim();

      if (name.length < 2 || phone.replace(/\D/g, "").length < 9) {
        statusBox.className = "form-status e";
        statusBox.textContent = "فضلاً اكتب اسمك الكريم ورقم جوال صحيح لنتمكن من تأكيد طلب السيارة.";
        return;
      }

      const cartSummaryText = cart.length > 0
        ? cart.map((i) => `${i.name} (العدد: ${i.qty})`).join(" + ")
        : "طلب زيارة معاينة مجانية بدون منتجات محددة";

      const fullDetails = [
        `نوع الخدمة: ${serviceMode}`,
        `محتويات سلة السيارة: ${cartSummaryText}`,
        userNotes ? `ملاحظات العميل: ${userNotes}` : ""
      ].filter(Boolean).join(" | ");

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = "<span>جاري تسجيل الطلب وتوجيه سيارة الخدمة...</span>";
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
            estimated_price: "يؤكد السعر النهائي مع المعاينة والتركيب",
            details: fullDetails
          })
        });

        const result = await res.json();
        if (!res.ok || !result.ok) {
          throw new Error(result.message || "تعذر إرسال الطلب حالياً.");
        }

        statusBox.className = "form-status success-box";
        statusBox.innerHTML = `
          <strong>${result.message}</strong>
          <div style="margin-top:6px;font-size:13.5px;">
            تم حفظ تفاصيل السلة والطلب في النظام، وجاري فتح محادثة واتساب مباشرة مع فريق جدة...
          </div>
        `;

        if (result.whatsapp_url) {
          setTimeout(() => {
            window.open(result.whatsapp_url, "_blank", "noopener,noreferrer");
          }, 450);
        }
      } catch (err) {
        statusBox.className = "form-status e";
        statusBox.textContent = err.message || "حدث خطأ في الاتصال، حاول مرة أخرى.";
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = "<span>تأكيد الطلب وإرساله لسيارة الخدمة (حفظ + واتساب)</span>";
        }
      }
    });
  }
});
