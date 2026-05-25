/**
 * SmartCaptcha callback (used by Yandex widget script)
 */
window.onSmartCaptchaSuccess = function (token) {
  var tokenInput = document.getElementById('smartcaptcha-token');
  if (tokenInput) {
    tokenInput.value = token || '';
  }
};
window.onSmartCaptchaTokenExpired = function () {
  var tokenInput = document.getElementById('smartcaptcha-token');
  if (tokenInput) {
    tokenInput.value = '';
  }
};
window.onSmartCaptchaNetworkError = function () {
  var tokenInput = document.getElementById('smartcaptcha-token');
  if (tokenInput) {
    tokenInput.value = '';
  }
};

/**
 * Conference Site вЂ” Main JavaScript
 * Progressive enhancement: site works without JS, JS adds interactivity
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  function initSmartCaptchaFallback() {
    var container = document.getElementById('captcha-container');
    if (!container) return;
    if (container.getAttribute('data-rendered') === '1') return;

    var sitekey = container.getAttribute('data-sitekey') || '';
    if (!sitekey) return;

    if (window.smartCaptcha && typeof window.smartCaptcha.render === 'function') {
      try {
        window.smartCaptcha.render('captcha-container', {
          sitekey: sitekey,
          hl: 'ru',
          callback: 'onSmartCaptchaSuccess',
          'expired-callback': 'onSmartCaptchaTokenExpired',
          'network-error-callback': 'onSmartCaptchaNetworkError'
        });
        container.setAttribute('data-rendered', '1');
      } catch (e) {
        // Ignore render race (widget may already be auto-rendered).
      }
    }
  }

  setTimeout(initSmartCaptchaFallback, 200);
  setTimeout(initSmartCaptchaFallback, 1200);
  window.addEventListener('load', initSmartCaptchaFallback);

  /* ==========================================================================
     Navigation
     ========================================================================== */
  const nav = document.querySelector('.nav');
  const navToggle = document.querySelector('.nav__toggle');
  const navMobile = document.querySelector('.nav__mobile');
  const mobileLinks = navMobile ? navMobile.querySelectorAll('a') : [];

  // Scroll-aware nav background
  function updateNav() {
    if (!nav) return;
    if (window.scrollY > 50) {
      nav.classList.add('nav--scrolled');
    } else {
      nav.classList.remove('nav--scrolled');
    }
  }
  window.addEventListener('scroll', updateNav, { passive: true });
  updateNav();

  // Mobile menu toggle
  if (navToggle && navMobile) {
    navToggle.addEventListener('click', function () {
      const isOpen = navMobile.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', isOpen);
      // Toggle icon
      const icons = navToggle.querySelectorAll('svg');
      if (icons.length === 2) {
        icons[0].style.display = isOpen ? 'none' : 'block';
        icons[1].style.display = isOpen ? 'block' : 'none';
      }
    });

    mobileLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        navMobile.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
        const icons = navToggle.querySelectorAll('svg');
        if (icons.length === 2) {
          icons[0].style.display = 'block';
          icons[1].style.display = 'none';
        }
      });
    });
  }

  /* ==========================================================================
     Speakers Carousel
     ========================================================================== */
  const speakersTrack = document.querySelector('.speakers__track');
  const speakerCards = speakersTrack ? Array.from(speakersTrack.children) : [];
  const speakerPrev = document.querySelector('.speakers__arrow--prev');
  const speakerNext = document.querySelector('.speakers__arrow--next');
  const speakerDots = document.querySelectorAll('.speakers__dot');
  let speakerIndex = 0;

  function getVisibleCount() {
    if (window.innerWidth >= 1024) return 4;
    if (window.innerWidth >= 640) return 2;
    return 1;
  }

  function updateSpeakers() {
    if (!speakersTrack || speakerCards.length === 0) return;

    const visibleCount = getVisibleCount();
    const total = speakerCards.length;
    const maxIndex = Math.max(0, total - visibleCount);
    if (speakerIndex > maxIndex) speakerIndex = maxIndex;

    const gapPx = 24; // matches CSS gap: 1.5rem
    const containerWidth = speakersTrack.parentElement ? speakersTrack.parentElement.clientWidth : speakersTrack.clientWidth;
    const trackStyles = window.getComputedStyle(speakersTrack);
    const paddingLeft = parseFloat(trackStyles.paddingLeft) || 0;
    const paddingRight = parseFloat(trackStyles.paddingRight) || 0;
    const usableWidth = Math.max(0, containerWidth - paddingLeft - paddingRight);
    const cardWidth = Math.max(0, (usableWidth - gapPx * (visibleCount - 1)) / visibleCount);

    speakersTrack.style.setProperty('--speaker-card-width', cardWidth + 'px');
    speakersTrack.style.transform = 'translateX(' + (-(cardWidth + gapPx) * speakerIndex) + 'px)';

    speakerDots.forEach(function (dot, i) {
      dot.classList.toggle('is-active', i === speakerIndex);
      dot.style.display = i <= maxIndex ? '' : 'none';
    });
  }

  if (speakerPrev) {
    speakerPrev.addEventListener('click', function () {
      const visibleCount = getVisibleCount();
      const maxIndex = Math.max(0, speakerCards.length - visibleCount);
      speakerIndex = speakerIndex <= 0 ? maxIndex : speakerIndex - 1;
      updateSpeakers();
    });
  }

  if (speakerNext) {
    speakerNext.addEventListener('click', function () {
      const visibleCount = getVisibleCount();
      const maxIndex = Math.max(0, speakerCards.length - visibleCount);
      speakerIndex = speakerIndex >= maxIndex ? 0 : speakerIndex + 1;
      updateSpeakers();
    });
  }

  speakerDots.forEach(function (dot, i) {
    dot.addEventListener('click', function () {
      speakerIndex = i;
      updateSpeakers();
    });
  });

  if (speakerCards.length > 0) {
    updateSpeakers();
    window.addEventListener('resize', updateSpeakers);
  }

  /* ==========================================================================
     Schedule Tabs
     ========================================================================== */
  const scheduleTabs = document.querySelectorAll('.schedule__tab');
  const schedulePanels = document.querySelectorAll('.schedule__panel');

  scheduleTabs.forEach(function (tab, i) {
    tab.addEventListener('click', function () {
      scheduleTabs.forEach(function (t) { t.classList.remove('is-active'); });
      schedulePanels.forEach(function (p) { p.classList.remove('is-active'); });
      tab.classList.add('is-active');
      if (schedulePanels[i]) schedulePanels[i].classList.add('is-active');
    });
  });

  /* ==========================================================================
     Gallery Accordion + Lightbox
     ========================================================================== */
  const galleryBtns = document.querySelectorAll('.gallery__year-btn');
  const galleryPanels = document.querySelectorAll('.gallery__images');
  const lightbox = document.querySelector('.lightbox');
  const lightboxImg = lightbox ? lightbox.querySelector('.lightbox__img') : null;
  const lightboxCounter = lightbox ? lightbox.querySelector('.lightbox__counter') : null;
  const lightboxClose = lightbox ? lightbox.querySelector('.lightbox__close') : null;
  const lightboxPrev = lightbox ? lightbox.querySelector('.lightbox__arrow--prev') : null;
  const lightboxNext = lightbox ? lightbox.querySelector('.lightbox__arrow--next') : null;

  // Collect all gallery images for lightbox navigation
  var allGalleryImages = [];
  document.querySelectorAll('.gallery__thumb img').forEach(function (img) {
    allGalleryImages.push({ src: img.getAttribute('src'), alt: img.getAttribute('alt') || '' });
  });
  var lightboxIndex = 0;

  // Accordion
  galleryBtns.forEach(function (btn, i) {
    btn.addEventListener('click', function () {
      var isOpen = btn.classList.toggle('is-open');
      if (galleryPanels[i]) galleryPanels[i].classList.toggle('is-open', isOpen);
      // Close others
      galleryBtns.forEach(function (otherBtn, j) {
        if (j !== i) {
          otherBtn.classList.remove('is-open');
          if (galleryPanels[j]) galleryPanels[j].classList.remove('is-open');
        }
      });
    });
  });

  // Open lightbox
  document.querySelectorAll('.gallery__thumb').forEach(function (thumb, i) {
    thumb.addEventListener('click', function () {
      lightboxIndex = i;
      showLightbox();
    });
  });

  function showLightbox() {
    if (!lightbox || !lightboxImg) return;
    var img = allGalleryImages[lightboxIndex];
    if (!img) return;
    lightboxImg.src = img.src;
    lightboxImg.alt = img.alt;
    if (lightboxCounter) {
      lightboxCounter.textContent = (lightboxIndex + 1) + ' / ' + allGalleryImages.length;
    }
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  if (lightboxPrev) {
    lightboxPrev.addEventListener('click', function () {
      lightboxIndex = (lightboxIndex - 1 + allGalleryImages.length) % allGalleryImages.length;
      showLightbox();
    });
  }
  if (lightboxNext) {
    lightboxNext.addEventListener('click', function () {
      lightboxIndex = (lightboxIndex + 1) % allGalleryImages.length;
      showLightbox();
    });
  }

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
    if (lightbox && lightbox.classList.contains('is-open')) {
      if (e.key === 'ArrowLeft' && lightboxPrev) lightboxPrev.click();
      if (e.key === 'ArrowRight' && lightboxNext) lightboxNext.click();
    }
  });

  // Close on backdrop click
  if (lightbox) {
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) closeLightbox();
    });
  }

    /* ==========================================================================
     Registration Form
     ========================================================================== */
  const regForm = document.querySelector('.reg__form');
  const participantBtns = document.querySelectorAll('.reg__selector-btn');
  const otherFields = document.getElementById('other-fields');
  const orgTypeSelect = document.getElementById('reg-org-type');
  const paymentBtns = document.querySelectorAll('.payment-toggle__btn');
  const categoryInput = document.getElementById('category-input');
  const isPaidInput = document.getElementById('is-paid-input');
  const paymentTypeInput = document.getElementById('payment-type-input');
  const innInput = document.getElementById('reg-inn');
  const plansReportInput = document.getElementById('reg-plans-report');
  const reportTopicGroup = document.getElementById('report-topic-group');
  const reportTopicInput = document.getElementById('reg-report-topic');
  const successBox = document.getElementById('reg-success-box');
  const successText = document.getElementById('reg-success-text');
  const formWrapper = document.getElementById('reg-form-wrapper');

  var category = categoryInput ? categoryInput.value : 'education';
  var paymentType = paymentTypeInput ? paymentTypeInput.value : 'company';

  participantBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      category = btn.dataset.type;
      participantBtns.forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');

      if (categoryInput) categoryInput.value = category;
      if (otherFields) {
        otherFields.style.display = category === 'other' ? '' : 'none';
      }
      if (isPaidInput) isPaidInput.value = category === 'other' ? '1' : '0';
      if (orgTypeSelect) orgTypeSelect.required = category === 'other';
      if (innInput) {
        innInput.required = category === 'other';
        if (category !== 'other') innInput.value = '';
      }
      if (plansReportInput) {
        if (category === 'other') {
          plansReportInput.checked = false;
        }
      }
      updateReportTopicState();
    });
  });

  function updateReportTopicState() {
    var canReport = category !== 'other';
    var isChecked = !!(plansReportInput && plansReportInput.checked);

    if (reportTopicGroup) {
      reportTopicGroup.style.display = canReport && isChecked ? '' : 'none';
    }
    if (reportTopicInput) {
      reportTopicInput.required = canReport && isChecked;
      if (!(canReport && isChecked)) {
        reportTopicInput.value = '';
      }
    }
  }

  if (plansReportInput) {
    plansReportInput.addEventListener('change', updateReportTopicState);
  }

  paymentBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      paymentType = btn.dataset.payment;
      paymentBtns.forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (paymentTypeInput) paymentTypeInput.value = paymentType;
      updateInnShape();
    });
  });

  function updateInnShape() {
    if (innInput) {
      var isCompanyPayment = paymentType === 'company';
      innInput.maxLength = isCompanyPayment ? 10 : 12;
      innInput.placeholder = isCompanyPayment ? '1234567890' : '123456789012';
    }
  }

  if (innInput) {
    innInput.addEventListener('input', function () {
      this.value = this.value.replace(/\D/g, '');
    });
  }

  if (regForm) {
    updateInnShape();
    updateReportTopicState();

    regForm.addEventListener('submit', function (e) {
      e.preventDefault();

      var errors = [];
      var fullName = document.getElementById('reg-fullname');
      var email = document.getElementById('reg-email');
      var org = document.getElementById('reg-organization');
      var position = document.getElementById('reg-position');
      var phone = document.getElementById('reg-phone');
      var wantsPartner = document.getElementById('reg-partner');
      var plansReport = document.getElementById('reg-plans-report');
      var reportTopic = document.getElementById('reg-report-topic');
      var pdConsent = document.getElementById('reg-personal-data');
      var offerConsent = document.getElementById('reg-offer');
      var captchaTokenInput = document.querySelector('#captcha-container input[name="smart-token"]')
        || document.querySelector('input[name="smart-token"]');
      var captchaHiddenToken = document.getElementById('smartcaptcha-token');
      var smartToken = captchaHiddenToken && String(captchaHiddenToken.value || '').trim() !== ''
        ? String(captchaHiddenToken.value || '').trim()
        : (captchaTokenInput ? String(captchaTokenInput.value || '').trim() : '');
      var errorBox = regForm.querySelector('.reg__errors');
      var submitBtn = regForm.querySelector('button[type="submit"]');

      regForm.querySelectorAll('.form-input--error').forEach(function (el) {
        el.classList.remove('form-input--error');
      });

      if (errorBox) {
        errorBox.style.display = 'none';
        errorBox.innerHTML = '';
      }

      if (fullName && fullName.value.trim().length < 3) {
        errors.push('Укажите ФИО');
        fullName.classList.add('form-input--error');
      }
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        errors.push('Укажите корректный email');
        email.classList.add('form-input--error');
      }
      if (org && org.value.trim().length < 2) {
        errors.push('Укажите организацию');
        org.classList.add('form-input--error');
      }
      if (position && position.value.trim().length < 2) {
        errors.push('Укажите должность');
        position.classList.add('form-input--error');
      }
      if (phone && phone.value.replace(/\D/g, '').length < 10) {
        errors.push('Укажите корректный телефон');
        phone.classList.add('form-input--error');
      }

      if (category === 'other' && orgTypeSelect && orgTypeSelect.value === '') {
        errors.push('Выберите тип организации');
        orgTypeSelect.classList.add('form-input--error');
      }
      if (category === 'other' && innInput) {
        var digits = innInput.value.replace(/\D/g, '');
        var requiredLen = paymentType === 'company' ? 10 : 12;
        if (digits.length !== requiredLen) {
          errors.push('ИНН должен содержать ' + requiredLen + ' цифр');
          innInput.classList.add('form-input--error');
        }
      }
      if (category !== 'other' && plansReport && plansReport.checked && reportTopic) {
        if (reportTopic.value.trim().length < 3) {
          errors.push('Укажите тему выступления');
          reportTopic.classList.add('form-input--error');
        }
      }

      if (pdConsent && !pdConsent.checked) {
        errors.push('Необходимо согласие на обработку персональных данных');
      }
      if (offerConsent && !offerConsent.checked) {
        errors.push('Необходимо принять условия оферты');
      }
      if (document.getElementById('captcha-container') && smartToken === '') {
        errors.push('Подтвердите, что вы не робот');
      }

      if (errors.length > 0) {
        if (errorBox) {
          errorBox.innerHTML = '<ul>' + errors.map(function (err) { return '<li>' + err + '</li>'; }).join('') + '</ul>';
          errorBox.style.display = '';
        }
        return false;
      }

      var payload = {
        full_name: fullName ? fullName.value.trim() : '',
        email: email ? email.value.trim() : '',
        phone: phone ? phone.value.trim() : '',
        organization: org ? org.value.trim() : '',
        position: position ? position.value.trim() : '',
        category: category,
        org_type: category === 'other' && orgTypeSelect ? orgTypeSelect.value : null,
        is_paid: category === 'other',
        payment_type: category === 'other' ? paymentType : null,
        inn: category === 'other' && innInput ? innInput.value.replace(/\D/g, '') : null,
        wants_partner: category === 'other' && wantsPartner ? wantsPartner.checked : false,
        plans_report: category !== 'other' && plansReport ? plansReport.checked : false,
        report_topic: category !== 'other' && plansReport && plansReport.checked && reportTopic ? reportTopic.value.trim() : null,
        smart_token: smartToken
      };

      if (submitBtn) submitBtn.setAttribute('disabled', 'disabled');

      fetch('/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
        .then(function (response) {
          return response.json().then(function (data) {
            return { ok: response.ok, data: data };
          }).catch(function () {
            return { ok: response.ok, data: {} };
          });
        })
        .then(function (result) {
          if (!result.ok) {
            var serverError = result.data && result.data.error ? result.data.error : 'Не удалось отправить заявку';
            throw new Error(serverError);
          }

          if (formWrapper) formWrapper.style.display = 'none';
          if (successText) {
            successText.textContent = 'Спасибо за регистрацию. Ваша заявка №' + result.data.application_number + '.';
          }
          if (successBox) successBox.style.display = '';
        })
        .catch(function (error) {
          if (errorBox) {
            errorBox.innerHTML = '<ul><li>' + error.message + '</li></ul>';
            errorBox.style.display = '';
          }
          if (submitBtn) submitBtn.removeAttribute('disabled');
        });
    });
  }

  /* ==========================================================================
     Smooth scroll for anchor links (progressive enhancement)
     ========================================================================== */

  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        var offset = nav ? nav.offsetHeight : 0;
        var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });
});

