/**
 * SMART X — Interactive Experience Engine (Jeddah Edition)
 * Zero-Bootstrap • Interactive Smartphone Feed • Architectural Blueprint • Scroll Stacking
 */

document.addEventListener("DOMContentLoaded", () => {
  // 1. Scroll Progress Rail
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
  }, { threshold: 0.12 });
  document.querySelectorAll(".reveal").forEach((el) => revealObserver.observe(el));

  // 3. Mouse-Reactive Glow Cards
  document.querySelectorAll(".glow-card").forEach((card) => {
    card.addEventListener("mousemove", (e) => {
      const rect = card.getBoundingClientRect();
      card.style.setProperty("--mx", `${e.clientX - rect.left}px`);
      card.style.setProperty("--my", `${e.clientY - rect.top}px`);
    });
  });

  // 4. Magnetic Primary Buttons
  document.querySelectorAll(".magnetic").forEach((btn) => {
    btn.addEventListener("mousemove", (e) => {
      const rect = btn.getBoundingClientRect();
      const x = (e.clientX - rect.left - rect.width / 2) * 0.13;
      const y = (e.clientY - rect.top - rect.height / 2) * 0.17;
      btn.style.transform = `translate(${x}px, ${y}px)`;
    });
    btn.addEventListener("mouseleave", () => {
      btn.style.transform = "";
    });
  });

  // 5. Mobile Navigation
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

  // 6. Live Interactive Smartphone Surveillance Mockup
  const phoneChannels = {
    front: {
      subtitle: "الواجهة والمدخل الرئيسي",
      res: "4K • ملون ليلي",
      toast: "الباب الرئيسي مؤمّن — رؤية صافية 100%",
      img: "https://images.pexels.com/photos/37591155/pexels-photo-37591155.jpeg?auto=compress&cs=tinysrgb&w=1200"
    },
    villa: {
      subtitle: "محيط الفيلا والأسوار",
      res: "4K • بانوراما 180",
      toast: "تغطية كاملة للأسوار والارتدادات بدون نقاط عمياء",
      img: "https://images.pexels.com/photos/274895/pexels-photo-274895.jpeg?auto=compress&cs=tinysrgb&w=1200"
    },
    shop: {
      subtitle: "المعرض والمكاتب الداخلية",
      res: "4K • صوت وصورة",
      toast: "صوت نقي وتسجيل مستمر لصالة العرض والمكاتب",
      img: "https://images.pexels.com/photos/29866272/pexels-photo-29866272/free-photo-of-outdoor-security-camera-on-concrete-wall.jpeg?auto=compress&cs=tinysrgb&w=1200"
    },
    gate: {
      subtitle: "البوابة وموقف السيارات",
      res: "قراءة لوحات • ليلي",
      toast: "توثيق فوري لدخول وخروج المركبات بدقة عالية",
      img: "https://images.pexels.com/photos/12571569/pexels-photo-12571569.jpeg?auto=compress&cs=tinysrgb&w=1200"
    }
  };

  const phoneClock = document.getElementById("phoneClock");
  if (phoneClock) {
    const tick = () => {
      phoneClock.textContent = new Date().toTimeString().split(" ")[0];
    };
    tick();
    setInterval(tick, 1000);
  }

  function switchPhoneChannel(key) {
    const ch = phoneChannels[key];
    if (!ch) return;

    document.querySelectorAll("[data-channel]").forEach((el) => {
      el.classList.toggle("active", el.getAttribute("data-channel") === key);
    });

    const img = document.getElementById("phoneFeedImg");
    const sub = document.getElementById("phoneCamSubtitle");
    const res = document.getElementById("phoneFeedRes");
    const toast = document.getElementById("phoneToastText");

    if (img) {
      img.style.opacity = "0.4";
      setTimeout(() => {
        img.src = ch.img;
        img.style.opacity = "1";
      }, 150);
    }
    if (sub) sub.textContent = ch.subtitle;
    if (res) res.textContent = ch.res;
    if (toast) toast.textContent = ch.toast;
  }

  document.querySelectorAll("[data-channel]").forEach((btn) => {
    btn.addEventListener("click", () => switchPhoneChannel(btn.getAttribute("data-channel")));
  });

  // 7. Interactive Architectural Blueprint
  const blueprintData = {
    gate: {
      step: "النقطة 01 من 05",
      title: "البوابة والمدخل الرئيسي",
      desc: "أهم نقطة في أي بيت أو منشأة. نركب هنا كاميرا بولت أو بانوراما عريضة برؤية ليلية ملونة، تكشف لك الباب والشارع بوضوح تام وتنبهك فوراً على جوالك.",
      cam: "بانوراما 180 درجة / بولت 4K ملونة",
      cov: "تغطية عريضة للواجهة والمدخل حتى 60 متر",
      feat: "إشعار فوري على الجوال عند اقتراب أي شخص من الباب"
    },
    perimeter: {
      step: "النقطة 02 من 05",
      title: "زوايا السور والارتدادات الجانبية",
      desc: "حماية متكاملة للأسوار الجانبية والخلفية بكاميرات خارجية قوية تتحمل حرارة ورطوبة جدة، مرتبة بزوايا متقاطعة تمنع وجود أي نقطة عمياء حول المبنى.",
      cam: "كاميرا بولت خارجية مقاومة لحرارة جدة",
      cov: "تغطية طولية دقيقة للأسوار والممرات الجانبية",
      feat: "رصد دقيق للحركة على السور وتجاهل حركة الأشجار أو الظلال"
    },
    indoor: {
      step: "النقطة 03 من 05",
      title: "الصالات والممرات الداخلية",
      desc: "كاميرات قبة مدمجة بتصميم هادئ تندمج مع الأسقف الجبسية والديكورات الحديثة في البيوت والمكاتب وصالات العرض مع تسجيل صوتي نقي.",
      cam: "كاميرا قبة داخلية للأسقف (دوم 4K)",
      cov: "زاوية رؤية واسعة 120 درجة للغرف والممرات",
      feat: "مايكروفون مدمج عالي النقاء ورؤية ليلية صافية"
    },
    garage: {
      step: "النقطة 04 من 05",
      title: "موقف السيارات وبوابة الكراج",
      desc: "رؤية دقيقة لحركة دخول وخروج المركبات وتأمين السيارات المتوقفة، مع معالجة ذكية تمنع انعكاس أنوار السيارات ليلاً لقراءة التفاصيل بوضوح.",
      cam: "كاميرا قراءة لوحات / بولت عالية الوضوح",
      cov: "تركيز مباشر على مسار المركبات ومواقف الكراج",
      feat: "توثيق كامل لأوقات الدخول والخروج وحماية المركبات"
    },
    roof: {
      step: "النقطة 05 من 05",
      title: "التغطية الشاملة 360 درجة للساحات والسطح",
      desc: "للأسطح والفلل الكبيرة والمستودعات المفتوحة، نركب كاميرا متحركة شاملة تعطيك حرية تدوير العدسة والتقريب البصري المباشر من تطبيق الجوال.",
      cam: "كاميرا متحركة شاملة (PTZ 360)",
      cov: "دوران كامل 360 درجة ومدى رؤية يصل إلى 150 متر",
      feat: "تتبع تلقائي للأجسام المتحركة وتحكم فوري عن بُعد"
    }
  };

  function selectBlueprintNode(key) {
    const data = blueprintData[key];
    if (!data) return;

    document.querySelectorAll(".bp-node").forEach((n) => n.classList.toggle("active", n.getAttribute("data-bp") === key));
    document.querySelectorAll(".bp-tab").forEach((t) => t.classList.toggle("active", t.getAttribute("data-bp") === key));
    document.querySelectorAll(".bp-cone").forEach((c) => c.classList.toggle("active", c.id === `cone-${key}`));

    const setEl = (id, text) => {
      const el = document.getElementById(id);
      if (el) el.textContent = text;
    };

    setEl("bpActiveName", data.title);
    setEl("bpStepNum", data.step);
    setEl("bpTitle", data.title);
    setEl("bpDesc", data.desc);
    setEl("bpCamType", data.cam);
    setEl("bpCoverage", data.cov);
    setEl("bpFeature", data.feat);
  }

  document.querySelectorAll("[data-bp]").forEach((el) => {
    el.addEventListener("click", () => selectBlueprintNode(el.getAttribute("data-bp")));
  });

  // 8. Interactive 4K Clarity Slider
  const compRange = document.getElementById("compRange");
  const compBeforeLayer = document.getElementById("compBeforeLayer");
  const compHandle = document.getElementById("compHandle");
  const comparisonStage = document.getElementById("comparisonStage");

  if (compRange && compBeforeLayer && compHandle) {
    compRange.addEventListener("input", (e) => {
      const pct = `${e.target.value}%`;
      compBeforeLayer.style.width = pct;
      compHandle.style.left = pct;
    });
  }

  document.querySelectorAll(".v-mode").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".v-mode").forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      if (!comparisonStage) return;

      const preset = btn.getAttribute("data-vision-preset");
      comparisonStage.classList.toggle("ai-active", preset === "ai");
      comparisonStage.classList.toggle("zoom-active", preset === "zoom");

      if (preset === "ai" && compRange) {
        compRange.value = "22";
        compRange.dispatchEvent(new Event("input"));
      } else if (preset === "colorvu" && compRange) {
        compRange.value = "48";
        compRange.dispatchEvent(new Event("input"));
      }
    });
  });

  // 9. Camera Showcase Filter
  const filterPills = document.querySelectorAll(".filter-pill");
  const camCards = document.querySelectorAll(".cam-card");

  filterPills.forEach((pill) => {
    pill.addEventListener("click", () => {
      filterPills.forEach((p) => p.classList.remove("active"));
      pill.classList.add("active");
      const filter = pill.getAttribute("data-filter");
      camCards.forEach((card) => {
        const show = filter === "all" || card.getAttribute("data-category") === filter;
        card.classList.toggle("is-hidden", !show);
      });
    });
  });

  // Form Helpers
  const quoteSection = document.getElementById("quote");
  const detailsInput = document.getElementById("d");
  const districtInput = document.getElementById("district");

  function scrollToQuote() {
    if (quoteSection) {
      quoteSection.scrollIntoView({ behavior: "smooth" });
      setTimeout(() => {
        const nameEl = document.getElementById("n");
        if (nameEl) nameEl.focus();
      }, 550);
    }
  }

  document.querySelectorAll(".select-camera-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const name = btn.getAttribute("data-cam-name") || "";
      if (detailsInput) detailsInput.value = `أرغب في معاينة ميدانية لموديل: ${name}`;
      scrollToQuote();
    });
  });

  // 10. Jeddah Neighborhood Coverage Tabs
  const zoneTabs = document.querySelectorAll(".zone-tab");
  const zonePanels = document.querySelectorAll(".zone-panel");

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
      scrollToQuote();
    });
  });

  // 11. Async PHP Lead Submission + Instant WhatsApp
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
      const cameras = document.getElementById("c")?.value || "يحدد بعد المعاينة المجانية";
      const preferredDate = document.getElementById("prefDate")?.value || "في أقرب وقت متاح";
      const details = (detailsInput?.value || "").trim();

      if (name.length < 2 || phone.replace(/\D/g, "").length < 9) {
        statusBox.className = "form-status e";
        statusBox.textContent = "فضلاً اكتب اسمك ورقم جوال صحيح عشان نقدر نتواصل معك.";
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = "<span>جاري تسجيل طلب الزيارة الميدانية...</span>";
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
            cameras,
            resolution: "يحدد خلال المعاينة المجانية",
            preferred_date: preferredDate,
            estimated_price: "يحدد بعد المعاينة المجانية",
            details
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
            تم حفظ طلبك، وجاري فتح محادثة واتساب مباشرة مع فريق جدة...
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
          submitBtn.innerHTML = "<span>أرسل الطلب وتواصل معنا عبر واتساب</span>";
        }
      }
    });
  }
});
