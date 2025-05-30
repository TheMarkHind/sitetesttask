
; /* Start:"a:4:{s:4:"full";s:62:"/local/templates/templatemain/assets/js/main.js?17485195061333";s:6:"source";s:47:"/local/templates/templatemain/assets/js/main.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
var mobileHeader = document.getElementById('mobileHeader');
var mobileFooter = document.getElementById('mobileFooter');
var pageContent = document.getElementById('pageContent');
var mobileMenu = document.getElementById('mobileMenu');

document.getElementById('menuToggle').addEventListener('DOMContentLoaded', 'click', function () {
    // Переключаем классы
    mobileHeader.classList.remove('carcass-page-mobile');
    mobileHeader.classList.add('hidden');
    mobileFooter.classList.remove('carcass-page-mobile');
    mobileFooter.classList.add('hidden');
    pageContent.classList.remove('usage-page', 'usage-page-m');
    pageContent.classList.add('hidden');
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('main-menu-mobile');
});

document.getElementById('closeMenuToggle').addEventListener('click', function () {
    // Переключаем классы
    mobileHeader.classList.remove('hidden');
    mobileHeader.classList.add('carcass-page-mobile');
    mobileFooter.classList.remove('hidden');
    mobileFooter.classList.add('carcass-page-mobile');
    pageContent.classList.remove('hidden');
    pageContent.classList.add('usage-page', 'usage-page-m');
    mobileMenu.classList.remove('main-menu-mobile');
    mobileMenu.classList.add('hidden');
});
/* End */
;; /* /local/templates/templatemain/assets/js/main.js?17485195061333*/
