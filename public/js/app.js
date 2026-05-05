/**
 * Dhaka Magazine — Vanilla JS interactions
 *
 * Features:
 *  - Mobile menu toggle
 *  - Sticky navbar on scroll with slide animation
 *  - Scroll-to-top button
 *  - Tab switching for sports section
 */

document.addEventListener('DOMContentLoaded', function () {

  // ── Mobile menu toggle ───────────────────────────────────
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const mobileMenu   = document.getElementById('mobile-menu');
  const iconMenu     = document.getElementById('icon-menu');
  const iconClose    = document.getElementById('icon-close');
  const body         = document.body;

  if (hamburgerBtn && mobileMenu) {
    hamburgerBtn.addEventListener('click', function () {
      var isOpen = !mobileMenu.classList.contains('hidden');
      mobileMenu.classList.toggle('hidden', isOpen);
      body.style.overflow = isOpen ? '' : 'hidden';
      if (iconMenu) iconMenu.classList.toggle('hidden', !isOpen);
      if (iconClose) iconClose.classList.toggle('hidden', isOpen);
    });

    var mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        mobileMenu.classList.add('hidden');
        body.style.overflow = '';
        if (iconMenu) iconMenu.classList.remove('hidden');
        if (iconClose) iconClose.classList.add('hidden');
      });
    });
  }

  // ── Sticky nav on scroll ─────────────────────────────────
  var siteNav   = document.getElementById('site-nav');
  var siteHeader = document.getElementById('site-header');

  if (siteNav) {
    var scrollThreshold = 120;
    var ticking = false;

    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          var scrolled = window.scrollY > scrollThreshold;
          siteNav.classList.toggle('sticky', scrolled);

          if (scrolled && siteHeader) {
            siteHeader.style.marginBottom = siteNav.offsetHeight + 'px';
          } else if (siteHeader) {
            siteHeader.style.marginBottom = '0';
          }

          ticking = false;
        });
        ticking = true;
      }
    });
  }

  // ── Scroll-to-top button ─────────────────────────────────
  var scrollBtn = document.createElement('button');
  scrollBtn.id = 'scroll-to-top';
  scrollBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m18 15-6-6-6 6"/></svg>';
  scrollBtn.style.cssText = 'position:fixed;bottom:24px;right:24px;width:44px;height:44px;border-radius:50%;background:#e2231a;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,0.3);opacity:0;pointer-events:none;transition:opacity 0.3s,transform 0.3s;transform:translateY(10px);z-index:999;cursor:pointer;';
  document.body.appendChild(scrollBtn);

  var scrollBtnTicking = false;
  window.addEventListener('scroll', function () {
    if (!scrollBtnTicking) {
      window.requestAnimationFrame(function () {
        var visible = window.scrollY > 400;
        scrollBtn.style.opacity = visible ? '1' : '0';
        scrollBtn.style.transform = visible ? 'translateY(0)' : 'translateY(10px)';
        scrollBtn.style.pointerEvents = visible ? 'auto' : 'none';
        scrollBtnTicking = false;
      });
      scrollBtnTicking = true;
    }
  });

  scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // ── Tab switching (sports section "পঠিত/আলোচিত/সুখবর") ──
  var tabContainers = document.querySelectorAll('.tab-group');
  tabContainers.forEach(function (container) {
    var tabs = container.querySelectorAll('button[data-tab]');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var target = this.getAttribute('data-tab');
        tabs.forEach(function (t) {
          t.classList.remove('border-[#e2231a]', 'text-[#111]');
          t.classList.add('border-transparent', 'text-[#888]');
          t.style.borderBottomColor = 'transparent';
        });
        this.classList.add('border-[#e2231a]', 'text-[#111]');
        this.classList.remove('border-transparent', 'text-[#888]');
        this.style.borderBottomColor = '#e2231a';

        var panels = container.querySelectorAll('[data-panel]');
        panels.forEach(function (panel) {
          panel.style.display = panel.getAttribute('data-panel') === target ? '' : 'none';
        });
      });
    });
  });

});
