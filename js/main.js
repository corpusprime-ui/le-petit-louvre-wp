(function () {
  'use strict';

  /* ------------------------------------------
     Video Scrub on Scroll — GSAP ScrollTrigger
  ------------------------------------------ */
  const heroVideo  = document.getElementById('heroVideo');
  const heroScrub  = document.getElementById('heroScrub');
  const stickyNav  = document.getElementById('stickyNav');
  const scrollTop  = document.getElementById('scrollTop');

  if (heroVideo && heroScrub && typeof gsap !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    /* Figer la vidéo sur la 1ère frame */
    heroVideo.pause();
    heroVideo.currentTime = 0;

    ScrollTrigger.create({
      trigger      : heroScrub,
      start        : 'top top',
      end          : 'bottom bottom',
      pin          : '#hero',
      anticipatePin: 1,
      scrub        : 1,
      onUpdate(self) {
        if (heroVideo.readyState >= 2 && heroVideo.duration) {
          heroVideo.currentTime = heroVideo.duration * self.progress;
        }
      },
      onLeave() {
        stickyNav && stickyNav.classList.add('visible');
      },
      onEnterBack() {
        stickyNav && stickyNav.classList.remove('visible');
      },
    });

    /* Scroll-to-top visibility après le scrub */
    ScrollTrigger.create({
      trigger : heroScrub,
      start   : 'bottom 80%',
      onEnter : () => scrollTop && scrollTop.classList.add('visible'),
      onLeaveBack: () => scrollTop && scrollTop.classList.remove('visible'),
    });

  } else {
    /* Fallback sans GSAP — sticky nav au scroll classique */
    const heroEl = document.getElementById('hero');
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      if (stickyNav) stickyNav.classList.toggle('visible', y > (heroEl ? heroEl.offsetHeight * 0.85 : 600));
      if (scrollTop) scrollTop.classList.toggle('visible', y > 400);
    }, { passive: true });
  }

  /* Speakeasy crossfade — transition douce toutes les 6s */
  const speakeasyBgs = document.querySelectorAll('.speakeasy-bg');
  if (speakeasyBgs.length > 1) {
    let spCurrent = 0;
    setInterval(() => {
      speakeasyBgs[spCurrent].classList.remove('active');
      spCurrent = (spCurrent + 1) % speakeasyBgs.length;
      speakeasyBgs[spCurrent].classList.add('active');
    }, 6000);
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
  ------------------------------------------ */
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', e => {
      const rect   = btn.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.className = 'ripple';
      ripple.style.left = (e.clientX - rect.left) + 'px';
      ripple.style.top  = (e.clientY - rect.top)  + 'px';
      btn.appendChild(ripple);
      setTimeout(() => ripple.remove(), 650);
    });
  });

  /* ------------------------------------------
     Smooth scroll-to-top
  ------------------------------------------ */
  document.getElementById('scrollTop').addEventListener('click', e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  const scrollHintBtn = document.getElementById('heroScrollHint');
  if (scrollHintBtn) {
    scrollHintBtn.addEventListener('click', () => {
      const scrub = document.getElementById('heroScrub');
      if (scrub) {
        window.scrollTo({ top: scrub.offsetTop + scrub.offsetHeight, behavior: 'smooth' });
      } else {
        const target = document.getElementById(scrollHintBtn.dataset.target || 'presentation');
        if (target) target.scrollIntoView({ behavior: 'smooth' });
      }
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

    window.addEventListener('resize', () => { setSlideWidths(); jump(current); });
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
  const oliveBranch = document.getElementById('oliveBranch');
  if (oliveBranch) {
    const oliveIo = new IntersectionObserver(([entry]) => {
      oliveBranch.classList.toggle('in-view', entry.isIntersecting);
    }, { threshold: 0.15 });
    oliveIo.observe(document.getElementById('privatisation'));
  }

  /* ------------------------------------------
     Mobile hamburger menu
  ------------------------------------------ */
  const mobileMenuBtn  = document.getElementById('mobileMenuBtn');
  const stickyMenuBtn  = document.getElementById('stickyMenuBtn');
  const overlay        = document.getElementById('mobileNavOverlay');
  const allMenuBtns    = [mobileMenuBtn, stickyMenuBtn].filter(Boolean);

  function openMenu() {
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('menu-open');
    allMenuBtns.forEach(b => b.classList.add('open'));
    mobileMenuBtn && mobileMenuBtn.setAttribute('aria-label', 'Fermer le menu');
    stickyMenuBtn && stickyMenuBtn.setAttribute('aria-label', 'Fermer le menu');
  }
  function closeMenu() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
    document.body.classList.remove('menu-open');
    allMenuBtns.forEach(b => b.classList.remove('open'));
    mobileMenuBtn && mobileMenuBtn.setAttribute('aria-label', 'Ouvrir le menu');
    stickyMenuBtn && stickyMenuBtn.setAttribute('aria-label', 'Ouvrir le menu');
  }
  allMenuBtns.forEach(btn =>
    btn.addEventListener('click', () =>
      overlay.classList.contains('open') ? closeMenu() : openMenu()
    )
  );
  document.getElementById('overlayCloseBtn').addEventListener('click', closeMenu);
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
      const show = window.scrollY > 150;
      floatReserve.classList.toggle('visible', show);
    }
    window.addEventListener('scroll', updateFloatBtn, { passive: true });
    updateFloatBtn();
  }

})();
