(function () {
  'use strict';

  const stickyNav   = document.getElementById('stickyNav');
  const scrollTop   = document.getElementById('scrollTop');

  /* Sticky nav + scroll-to-top — visibilité au scroll */
  const heroEl = document.getElementById('hero');
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    if (stickyNav) stickyNav.classList.toggle('visible', y > (heroEl ? heroEl.offsetHeight * 0.85 : 600));
    if (scrollTop) scrollTop.classList.toggle('visible', y > 400);
  }, { passive: true });

  /* Respect de prefers-reduced-motion — désactive toutes les animations auto */
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Hero-bg crossfade — pages internes (Carte, Bar…) */
  const heroBgs = document.querySelectorAll('.hero-bg');
  if (heroBgs.length > 1 && !reducedMotion) {
    let hbCurrent = 0;
    setInterval(() => {
      heroBgs[hbCurrent].classList.remove('active');
      hbCurrent = (hbCurrent + 1) % heroBgs.length;
      heroBgs[hbCurrent].classList.add('active');
    }, 5000);
  }

  /* Speakeasy crossfade — transition douce toutes les 6s */
  const speakeasyBgs = document.querySelectorAll('.speakeasy-bg');
  if (speakeasyBgs.length > 1 && !reducedMotion) {
    let spCurrent = 0;
    setInterval(() => {
      speakeasyBgs[spCurrent].classList.remove('active');
      spCurrent = (spCurrent + 1) % speakeasyBgs.length;
      speakeasyBgs[spCurrent].classList.add('active');
    }, 6000);
  }

  /* LPL brand story crossfade — transition douce toutes les 5s */
  const lplBgs = document.querySelectorAll('.lpl-bg');
  if (lplBgs.length > 1 && !reducedMotion) {
    let lplCurrent = 0;
    const lplNext = () => {
      lplBgs[lplCurrent].classList.remove('active');
      lplCurrent = (lplCurrent + 1) % lplBgs.length;
      lplBgs[lplCurrent].classList.add('active');
    };
    setTimeout(() => { lplNext(); setInterval(lplNext, 5000); }, 4000);
  }

  /* ------------------------------------------
     Intersection Observer — scroll reveals
  ------------------------------------------ */
  const revealTargets = document.querySelectorAll(
    '.reveal, .reveal-left, .reveal-right, .reveal-scale, .quote-text, .sep-line, .pres-photo'
  );
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -48px 0px' });
  revealTargets.forEach(el => io.observe(el));

  /* ------------------------------------------
     Button ripple
     — délégué sur document pour couvrir les boutons
       ajoutés dynamiquement (sliders, etc.)
     — ignoré sur mobile (touch-action gère le tap)
  ------------------------------------------ */
  const supportsHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (supportsHover) {
    document.addEventListener('click', e => {
      const btn = e.target.closest('.btn');
      if (!btn) return;
      const rect   = btn.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.className = 'ripple';
      ripple.style.left = (e.clientX - rect.left) + 'px';
      ripple.style.top  = (e.clientY - rect.top)  + 'px';
      btn.appendChild(ripple);
      setTimeout(() => ripple.remove(), 650);
    });
  }

  /* ------------------------------------------
     Smooth scroll-to-top
  ------------------------------------------ */
  if (scrollTop) {
    scrollTop.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  const scrollHintBtn = document.getElementById('heroScrollHint');
  if (scrollHintBtn) {
    scrollHintBtn.addEventListener('click', () => {
      const target = document.getElementById(scrollHintBtn.dataset.target || 'presentation');
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  }

  /* ------------------------------------------
     Infinite Slider Factory
  ------------------------------------------ */
  function createSlider({ trackId, prevId, nextId, dotsId, visible, autoplay, totalPad = 144, gap = 16, slideClass = '.gal-slide', dotClass = 'gal-dot' }) {
    const GAP      = gap;
    const track    = document.getElementById(trackId);
    if (!track) return;
    const prevBtn  = document.getElementById(prevId);
    const nextBtn  = document.getElementById(nextId);
    const dotsWrap = document.getElementById(dotsId);
    const viewport = track.parentElement;
    const sliderEl = viewport.parentElement;

    const origSlides = [...track.querySelectorAll(slideClass)];
    const total      = origSlides.length;

    origSlides.slice(-visible).forEach(s => track.prepend(s.cloneNode(true)));
    origSlides.slice(0, visible).forEach(s => track.appendChild(s.cloneNode(true)));

    const allSlides = [...track.querySelectorAll(slideClass)];
    let current = visible, slideW = 0, timer = null, locked = false;

    origSlides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = dotClass + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', `Slide ${i + 1}`);
      dot.addEventListener('click', () => goTo(visible + i));
      dotsWrap.appendChild(dot);
    });
    const dots = [...dotsWrap.querySelectorAll('.' + dotClass.split(' ')[0])];

    function updateDots() {
      const real = ((current - visible) % total + total) % total;
      dots.forEach((d, i) => d.classList.toggle('active', i === real));
    }

    function setSlideWidths() {
      const isMobile = window.innerWidth < 854;
      const dv  = isMobile ? 1 : visible;
      const pad = isMobile ? 40 : totalPad;
      const availW = sliderEl.offsetWidth - pad;
      slideW = (availW - GAP * (dv - 1)) / dv;
      track.style.gap = GAP + 'px';
      allSlides.forEach(s => { s.style.width = slideW + 'px'; });
    }

    function getX(idx) { return -(idx * (slideW + GAP)); }

    function jump(idx) {
      track.classList.remove('is-animating');
      track.style.transform = `translateX(${getX(idx)}px)`;
      current = idx;
    }

    function goTo(idx, animate = true) {
      if (locked) return;
      locked = true; current = idx;
      if (animate) track.classList.add('is-animating');
      track.style.transform = `translateX(${getX(current)}px)`;
      updateDots();
      setTimeout(() => {
        if (current >= total + visible) jump(visible + (current - total - visible));
        else if (current < visible)     jump(total + current);
        locked = false;
      }, animate ? 620 : 0);
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }
    function startAuto() { clearInterval(timer); timer = setInterval(next, autoplay); }
    function stopAuto()  { clearInterval(timer); }

    setSlideWidths(); jump(visible); startAuto();

    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => { setSlideWidths(); jump(current); }, 150);
    });
    prevBtn.addEventListener('click', () => { stopAuto(); prev(); startAuto(); });
    nextBtn.addEventListener('click', () => { stopAuto(); next(); startAuto(); });
    viewport.addEventListener('mouseenter', stopAuto);
    viewport.addEventListener('mouseleave', startAuto);

    let touchX = 0;
    viewport.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
    viewport.addEventListener('touchend',   e => {
      const dx = e.changedTouches[0].clientX - touchX;
      if (Math.abs(dx) > 40) { stopAuto(); dx < 0 ? next() : prev(); startAuto(); }
    });
  }

  window.addEventListener('load', () => {
    requestAnimationFrame(() => {
      createSlider({ trackId: 'galTrack',   prevId: 'galPrev',   nextId: 'galNext',   dotsId: 'galDots',   visible: 4, autoplay: 3200, totalPad: 144 });
      createSlider({ trackId: 'platsTrack', prevId: 'platsPrev', nextId: 'platsNext', dotsId: 'platsDots', visible: 3, autoplay: 3800, totalPad: 144 });
      createSlider({ trackId: 'avisTrack',  prevId: 'avisPrev',  nextId: 'avisNext',  dotsId: 'avisDots',  visible: 2, autoplay: 4500, totalPad: 160, gap: 24, slideClass: '.avis-card', dotClass: 'avis-dot' });
    });
  });

  /* ------------------------------------------
     Gallery tilt on mouse move
  ------------------------------------------ */
  document.querySelectorAll('.gal-slide').forEach(card => {
    card.addEventListener('mousemove', e => {
      const rect = card.getBoundingClientRect();
      const dx   = (e.clientX - rect.left - rect.width  / 2) / rect.width;
      const dy   = (e.clientY - rect.top  - rect.height / 2) / rect.height;
      card.style.transform = `perspective(600px) rotateY(${dx * 8}deg) rotateX(${-dy * 8}deg) scale(1.02)`;
    });
    card.addEventListener('mouseleave', () => {
      card.style.transition = 'transform 500ms cubic-bezier(0.25,1,0.5,1)';
      card.style.transform  = '';
      setTimeout(() => { card.style.transition = ''; }, 500);
    });
  });

  /* ------------------------------------------
     Branche d'olivier — apparaît/disparaît au scroll
  ------------------------------------------ */
  const oliveBranch    = document.getElementById('oliveBranch');
  const privatisation  = document.getElementById('privatisation');
  if (oliveBranch && privatisation) {
    const oliveIo = new IntersectionObserver(([entry]) => {
      oliveBranch.classList.toggle('in-view', entry.isIntersecting);
    }, { threshold: 0.15 });
    oliveIo.observe(privatisation);
  }

  /* ------------------------------------------
     Mobile hamburger menu
  ------------------------------------------ */
  const mobileMenuBtn  = document.getElementById('mobileMenuBtn');
  const stickyMenuBtn  = document.getElementById('stickyMenuBtn');
  const overlay        = document.getElementById('mobileNavOverlay');
  const allMenuBtns    = [mobileMenuBtn, stickyMenuBtn].filter(Boolean);

  /* Éléments focusables dans l'overlay — pour le focus trap */
  const focusableSelectors = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

  function openMenu() {
    overlay.classList.add('open');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('menu-open');
    allMenuBtns.forEach(b => {
      b.classList.add('open');
      b.setAttribute('aria-expanded', 'true');
    });
    mobileMenuBtn && mobileMenuBtn.setAttribute('aria-label', 'Fermer le menu');
    stickyMenuBtn && stickyMenuBtn.setAttribute('aria-label', 'Fermer le menu');
    /* Focus sur le premier lien — accessibilité clavier */
    requestAnimationFrame(() => {
      const first = overlay.querySelector(focusableSelectors);
      if (first) first.focus();
    });
  }
  function closeMenu() {
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    document.body.classList.remove('menu-open');
    allMenuBtns.forEach(b => {
      b.classList.remove('open');
      b.setAttribute('aria-expanded', 'false');
    });
    mobileMenuBtn && mobileMenuBtn.setAttribute('aria-label', 'Ouvrir le menu');
    stickyMenuBtn && stickyMenuBtn.setAttribute('aria-label', 'Ouvrir le menu');
    /* Rend le focus au bouton déclencheur */
    const trigger = mobileMenuBtn || stickyMenuBtn;
    if (trigger) trigger.focus();
  }

  /* Focus trap — empêche le Tab de sortir du menu ouvert */
  overlay.addEventListener('keydown', e => {
    if (e.key !== 'Tab' || !overlay.classList.contains('open')) return;
    const focusable = [...overlay.querySelectorAll(focusableSelectors)];
    if (!focusable.length) return;
    const first = focusable[0], last = focusable[focusable.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault(); last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault(); first.focus();
    }
  });
  /* État initial aria-expanded */
  allMenuBtns.forEach(b => b.setAttribute('aria-expanded', 'false'));

  allMenuBtns.forEach(btn =>
    btn.addEventListener('click', () =>
      overlay.classList.contains('open') ? closeMenu() : openMenu()
    )
  );
  const overlayCloseBtn = document.getElementById('overlayCloseBtn');
  if (overlayCloseBtn) overlayCloseBtn.addEventListener('click', closeMenu);
  overlay.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
  // Fermer sur Escape
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

  /* ------------------------------------------
     Motion design — pages Carte & Bar
     (boissons · cocktails · vins · carte)
  ------------------------------------------ */
  (function () {
    const motionObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('in-view');
        motionObs.unobserve(entry.target);
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });

    /**
     * Regroupe les éléments par parent et leur applique un délai
     * en cascade via la CSS custom property --rd.
     */
    function stagger(selector, step) {
      const groups = new Map();
      document.querySelectorAll(selector).forEach(el => {
        const key = el.parentElement;
        if (!groups.has(key)) groups.set(key, []);
        groups.get(key).push(el);
      });
      groups.forEach(group => {
        group.forEach((el, i) => {
          el.classList.add('reveal-sm');
          el.style.setProperty('--rd', (i * step) + 's');
          motionObs.observe(el);
        });
      });
    }

    /* Page Carte — items menu */
    stagger('.carte-item', 0.06);

    /* Pages Bar — lignes de boissons / vins / cocktails */
    stagger('.vins-row',        0.05);
    stagger('.vins-row-single', 0.05);
    stagger('.vins-row-double', 0.05);
    stagger('.vins-header-row', 0.04);

    /* Sous-navigation (Boissons · Cocktails · Vins) */
    stagger('.carte-nav-link', 0.07);

    /* Titres de section → trait animé ::after */
    document.querySelectorAll('.carte-section-title').forEach(el => {
      el.classList.add('reveal-sm');
      motionObs.observe(el);
    });

    /* Titre "LA CARTE" + lignes décoratives */
    const mainTitle = document.querySelector('.carte-main-title');
    if (mainTitle) {
      mainTitle.classList.add('reveal-sm');
      mainTitle.style.setProperty('--rd', '0.1s');
      motionObs.observe(mainTitle);
    }
    document.querySelectorAll('.carte-title-line').forEach(el => motionObs.observe(el));

    /* Section DL + barre d'annonce */
    document.querySelectorAll('.carte-dl-section, .carte-announce-bar').forEach(el => {
      el.classList.add('reveal-sm');
      motionObs.observe(el);
    });

    /* Badges "Nouveauté" — scale-in */
    document.querySelectorAll('.carte-badge').forEach((el, i) => {
      el.style.setProperty('--rd', (i * 0.03) + 's');
      el.classList.add('reveal-scale');
      motionObs.observe(el);
    });
  }());

  /* ------------------------------------------
     Section label letter-spacing animation
  ------------------------------------------ */
  document.querySelectorAll('.section-label').forEach(label => {
    label.style.letterSpacing = '0px';
    label.style.opacity = '0';
    label.style.transition = 'opacity 700ms ease 200ms';
    label.classList.remove('reveal');
    const labelIo = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) {
        label.style.opacity = '1';
        setTimeout(() => {
          label.style.transition = 'letter-spacing 900ms cubic-bezier(0.16,1,0.3,1), opacity 700ms ease';
          label.style.letterSpacing = '4.2px';
        }, 50);
        labelIo.unobserve(label);
      }
    }, { threshold: 0.3 });
    labelIo.observe(label);
  });

  /* ------------------------------------------
     Bouton flottant Réserver — toutes les pages
     Apparaît après 220px de scroll, disparaît au footer
  ------------------------------------------ */
  const floatReserve = document.getElementById('floatReserve');
  if (floatReserve) {
    function updateFloatBtn() {
      const halfway = (document.documentElement.scrollHeight - window.innerHeight) / 2;
      floatReserve.classList.toggle('visible', window.scrollY > halfway);
    }
    window.addEventListener('scroll', updateFloatBtn, { passive: true });
    updateFloatBtn();
  }

  /* ──────────────────────────────────────────
     NEWSLETTER — Animation + AJAX
  ────────────────────────────────────────── */
  const nlForm = document.getElementById('footerNl');
  if (nlForm) {
    nlForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const input = this.querySelector('.nl-input');
      const btn   = this.querySelector('.nl-btn');
      const msg   = this.querySelector('.nl-msg');
      const email = input.value.trim();

      /* Validation email basique */
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        input.focus();
        msg.textContent = 'Entrez un email valide.';
        msg.classList.add('visible');
        return;
      }

      /* ─ État loading ─ */
      btn.classList.add('is-loading');
      btn.disabled = true;
      msg.classList.remove('visible');

      /* AJAX WordPress */
      const ajaxUrl = (typeof lplAjax !== 'undefined') ? lplAjax.ajaxurl : '/wp-admin/admin-ajax.php';
      const nonce   = (typeof lplAjax !== 'undefined') ? lplAjax.nonce   : '';
      const fd = new FormData();
      fd.append('action', 'lpl_newsletter_subscribe');
      fd.append('email',  email);
      fd.append('nonce',  nonce);

      fetch(ajaxUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
          /* ─ État success ─ */
          btn.classList.remove('is-loading');
          btn.classList.add('is-success');
          input.value = '';
          msg.textContent = data.data?.message || 'Merci, à bientôt !';
          msg.classList.add('visible');
          /* Reset après 5 s */
          setTimeout(() => {
            btn.classList.remove('is-success');
            btn.disabled = false;
            msg.classList.remove('visible');
          }, 5000);
        })
        .catch(() => {
          btn.classList.remove('is-loading');
          btn.disabled = false;
          msg.textContent = 'Erreur réseau, réessayez.';
          msg.classList.add('visible');
        });
    });
  }

})();
