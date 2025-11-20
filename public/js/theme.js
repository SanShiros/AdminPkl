/**
=========================================================================
Template Name: Datta Able - Tailwind Admin Template
File: themes.js (DISIMPLIFY BIAR THEME STABIL)
=========================================================================
*/

'use strict';

var rtl_flag = false;
var dark_flag = false;

// ======================= INIT =======================
document.addEventListener('DOMContentLoaded', function () {
  // 1) BACA THEME DARI localStorage
  let layout = 'light';
  if (typeof Storage !== 'undefined') {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || saved === 'light') {
      layout = saved;
    }
  }

  // 2) TERAPKAN THEME KE <html>
  layout_change(layout);

  // 3) SETUP SEMUA LISTENER (BUTTON, PRESET, DLL)
  init_theme_controls();
});

// ======================= SETUP CONTROL =======================
function init_theme_controls() {
  // ========== PRESET COLOR ==========
  var if_exist = document.querySelectorAll('.preset-color');
  if (if_exist.length) {
    var preset_color = document.querySelectorAll('.preset-color > a');
    for (var h = 0; h < preset_color.length; h++) {
      var c = preset_color[h];
      c.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var presetValue = targetElement.getAttribute('data-value');
        preset_change(presetValue);
      });
    }

    // ========== TOMBOL THEME (LIGHT/DARK) ==========
    var layout_btn = document.querySelectorAll('.theme-layout .btn');
    for (var t = 0; t < layout_btn.length; t++) {
      var btn = layout_btn[t];
      if (!btn) continue;

      btn.addEventListener('click', function (event) {
        event.stopPropagation();
        var targetElement = event.target;

        if (targetElement.tagName === 'SPAN') {
          targetElement = targetElement.parentNode;
        }

        // data-value="true" → light, selain itu → dark
        var layout = targetElement.getAttribute('data-value') === 'true'
          ? 'light'
          : 'dark';

        // cukup panggil layout_change, DI SANA otomatis set localStorage
        layout_change(layout);
      });
    }
  }

  // ========== SIMPLEBAR ==========
  if (document.querySelector('.pct-body')) {
    new SimpleBar(document.querySelector('.pct-body'));
  }

  // ========== RESET LAYOUT ==========
  var layout_reset = document.querySelector('#layoutreset');
  if (layout_reset) {
    layout_reset.addEventListener('click', function (e) {
      localStorage.clear();
      location.reload();
      localStorage.setItem('layout', 'vertical');
    });
  }

  // ========================================
  // HEADER COLOR
  var header_exist = document.querySelectorAll('.header-color');
  if (header_exist.length) {
    var header_color = document.querySelectorAll('.header-color > a');
    for (var h2 = 0; h2 < header_color.length; h2++) {
      var ch = header_color[h2];
      ch.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        header_change(temp);
      });
    }
  }

  // NAVBAR COLOR
  var navbar_exist = document.querySelectorAll('.navbar-color');
  if (navbar_exist.length) {
    var navbar_color = document.querySelectorAll('.navbar-color > a');
    for (var h3 = 0; h3 < navbar_color.length; h3++) {
      var cn = navbar_color[h3];
      cn.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        navbar_change(temp);
      });
    }
  }

  // LOGO COLOR
  var logo_exist = document.querySelectorAll('.logo-color');
  if (logo_exist.length) {
    var logo_color = document.querySelectorAll('.logo-color > a');
    for (var h4 = 0; h4 < logo_color.length; h4++) {
      var cl = logo_color[h4];
      cl.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        logo_change(temp);
      });
    }
  }

  // CAPTION COLOR
  var caption_exist = document.querySelectorAll('.caption-color');
  if (caption_exist.length) {
    var caption_color = document.querySelectorAll('.caption-color > a');
    for (var h5 = 0; h5 < caption_color.length; h5++) {
      var cc = caption_color[h5];
      cc.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        caption_change(temp);
      });
    }
  }

  // NAVBAR IMAGE
  var navimg_exist = document.querySelectorAll('.navbar-img');
  if (navimg_exist.length) {
    var navbar_img_color = document.querySelectorAll('.navbar-img > a');
    for (var h6 = 0; h6 < navbar_img_color.length; h6++) {
      var ci = navbar_img_color[h6];
      ci.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        nav_image_change(temp);
      });
    }
  }

  // DROPDOWN MENU ICON
  var drp_menu_icon_exist = document.querySelectorAll('.drp-menu-icon');
  if (drp_menu_icon_exist.length) {
    var drp_menu_icon_color = document.querySelectorAll('.drp-menu-icon > a');
    for (var h7 = 0; h7 < drp_menu_icon_color.length; h7++) {
      var cmi = drp_menu_icon_color[h7];
      cmi.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        drp_menu_icon_change(temp);
      });
    }
  }

  // DROPDOWN MENU LINK ICON
  var drp_menu_link_icon_exist = document.querySelectorAll('.drp-menu-link-icon');
  if (drp_menu_link_icon_exist.length) {
    var drp_menu_link_icon_color = document.querySelectorAll('.drp-menu-link-icon > a');
    for (var h8 = 0; h8 < drp_menu_link_icon_color.length; h8++) {
      var cmli = drp_menu_link_icon_color[h8];
      cmli.addEventListener('click', function (event) {
        var targetElement = event.target;
        if (targetElement.tagName === 'SPAN' || targetElement.tagName === 'I') {
          targetElement = targetElement.parentNode;
        }
        var temp = targetElement.getAttribute('data-value');
        drp_menu_link_icon_change(temp);
      });
    }
  }
}

// ======================= HELPER LAIN =======================

function layout_caption_change(value) {
  if (value == 'true') {
    document.getElementsByTagName('html')[0].setAttribute('data-pc-sidebar-caption', 'true');
  } else {
    document.getElementsByTagName('html')[0].setAttribute('data-pc-sidebar-caption', 'false');
  }

  var control = document.querySelector('.theme-nav-caption .btn.active');
  if (control) {
    control.classList.remove('active');
  }
  var newActiveButton = document.querySelector(`.theme-nav-caption .btn[data-value='${value}']`);
  if (newActiveButton) {
    newActiveButton.classList.add('active');
  }
}

function preset_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('class', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.preset-color > a.active').classList.remove('active');
    document.querySelector(".preset-color > a[data-value='" + value + "']").classList.add('active');
  }
}

function main_layout_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-layout', value);

  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    var activeLink = document.querySelector('.theme-main-layout > a.active');
    if (activeLink) {
      activeLink.classList.remove('active');
    }
    var newActiveLink = document.querySelector(".theme-main-layout > a[data-value='" + value + "']");
    if (newActiveLink) {
      newActiveLink.classList.add('active');
    }
  }
}

function layout_rtl_change(value) {
  var htmlElement = document.getElementsByTagName('html')[0];

  if (value === 'true') {
    rtl_flag = true;
    htmlElement.setAttribute('data-pc-direction', 'rtl');
    htmlElement.setAttribute('dir', 'rtl');
    htmlElement.setAttribute('lang', 'ar');

    var activeButton = document.querySelector('.theme-direction .btn.active');
    if (activeButton) {
      activeButton.classList.remove('active');
    }
    var rtlButton = document.querySelector(".theme-direction .btn[data-value='true']");
    if (rtlButton) {
      rtlButton.classList.add('active');
    }
  } else {
    rtl_flag = false;
    htmlElement.setAttribute('data-pc-direction', 'ltr');
    htmlElement.setAttribute('dir', 'ltr');
    htmlElement.removeAttribute('lang');

    var activeButton2 = document.querySelector('.theme-direction .btn.active');
    if (activeButton2) {
      activeButton2.classList.remove('active');
    }
    var ltrButton = document.querySelector(".theme-direction .btn[data-value='false']");
    if (ltrButton) {
      ltrButton.classList.add('active');
    }
  }
}

// ======================= FUNGSI UTAMA THEME =======================
function layout_change(layout) {
  // normalisasi
  if (layout !== 'dark' && layout !== 'light') {
    layout = 'light';
  }

  // SIMPAN PILIHAN USER
  if (typeof Storage !== 'undefined') {
    localStorage.setItem('theme', layout);
  }

  var htmlEl = document.getElementsByTagName('html')[0];

  // Set atribut utama
  htmlEl.setAttribute('data-pc-theme', layout);

  // hapus 'active' di tombol default
  var btn_control = document.querySelector('.theme-layout .btn[data-value="default"]');
  if (btn_control) {
    btn_control.classList.remove('active');
  }

  var isDark = layout === 'dark';
  dark_flag = isDark;

  var logoSrc = isDark ? '../assets/images/logo-white.svg' : '../assets/images/logo-dark.svg';

  function updateLogo(selector) {
    var element = document.querySelector(selector);
    if (element) {
      element.setAttribute('src', logoSrc);
    }
  }

  // Update logos
  // updateLogo('.pc-sidebar .m-header .logo-lg');
  updateLogo('.navbar-brand .logo-lg');
  updateLogo('.auth-main.v1 .auth-sidefooter img');
  updateLogo('.auth-logo');
  updateLogo('.footer-top .footer-logo');

  // atur tombol theme aktif
  var activeControl = document.querySelector('.theme-layout .btn.active');
  if (activeControl) {
    activeControl.classList.remove('active');
  }

  var newActiveControl = document.querySelector(
    `.theme-layout .btn[data-value='${isDark ? 'false' : 'true'}']`
  );
  if (newActiveControl) {
    newActiveControl.classList.add('active');
  }
}

// ======================= LAIN-LAIN =======================
function change_box_container(value) {
  var contentElement = document.querySelector('.pc-content');
  var footerElement = document.querySelector('.footer-wrapper');

  if (contentElement && footerElement) {
    if (value === 'true') {
      contentElement.classList.add('container');
      footerElement.classList.add('container');
      footerElement.classList.remove('container-fluid');
    } else {
      contentElement.classList.remove('container');
      footerElement.classList.remove('container');
      footerElement.classList.add('container-fluid');
    }

    var activeButton = document.querySelector('.theme-container .btn.active');
    if (activeButton) {
      activeButton.classList.remove('active');
    }

    var newActiveButton = document.querySelector(`.theme-container .btn[data-value='${value}']`);
    if (newActiveButton) {
      newActiveButton.classList.add('active');
    }
  }
}

function layout_theme_sidebar_change(value) {
  if (value == 'true') {
    document.getElementsByTagName('html')[0].setAttribute('data-pc-sidebar_theme', 'true');
    if (document.querySelector('.pc-sidebar .m-header .logo-lg')) {
      document.querySelector('.pc-sidebar .m-header .logo-lg').setAttribute(
        'src',
        '../assets/images/logo-dark.svg'
      );
    }
    var control = document.querySelector('.theme-nav-layout .btn.active');
    if (control) {
      document.querySelector('.theme-nav-layout .btn.active').classList.remove('active');
      document.querySelector(".theme-nav-layout .btn[data-value='true']").classList.add('active');
    }
  } else {
    document.getElementsByTagName('html')[0].setAttribute('data-pc-sidebar_theme', 'false');
    if (document.querySelector('.pc-sidebar .m-header .logo-lg')) {
      document.querySelector('.pc-sidebar .m-header .logo-lg').setAttribute(
        'src',
        '../assets/images/logo-white.svg'
      );
    }
    var control2 = document.querySelector('.theme-nav-layout .btn.active');
    if (control2) {
      document.querySelector('.theme-nav-layout .btn.active').classList.remove('active');
      document.querySelector(".theme-nav-layout .btn[data-value='false']").classList.add('active');
    }
  }
}

function header_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-header', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.header-color > a.active').classList.remove('active');
    document.querySelector(".header-color > a[data-value='" + value + "']").classList.add('active');
  }
}
function navbar_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-navbar', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.navbar-color > a.active').classList.remove('active');
    document.querySelector(".navbar-color > a[data-value='" + value + "']").classList.add('active');
  }
}
function logo_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-logo', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.logo-color > a.active').classList.remove('active');
    document.querySelector(".logo-color > a[data-value='" + value + "']").classList.add('active');
  }
}
function caption_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-caption', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.caption-color > a.active').classList.remove('active');
    document.querySelector(".caption-color > a[data-value='" + value + "']").classList.add('active');
  }
}
function drp_menu_icon_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-drp-menu-icon', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.drp-menu-icon > a.active').classList.remove('active');
    document.querySelector(".drp-menu-icon > a[data-value='" + value + "']").classList.add('active');
  }
}
function drp_menu_link_icon_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-drp-menu-link-icon', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.drp-menu-link-icon > a.active').classList.remove('active');
    document.querySelector(".drp-menu-link-icon > a[data-value='" + value + "']").classList.add('active');
  }
}
function nav_image_change(value) {
  document.getElementsByTagName('html')[0].setAttribute('data-pc-navimg', value);
  var control = document.querySelector('.pct-offcanvas');
  if (control) {
    document.querySelector('.navbar-img > a.active').classList.remove('active');
    document.querySelector(".navbar-img > a[data-value='" + value + "']").classList.add('active');
  }
}
