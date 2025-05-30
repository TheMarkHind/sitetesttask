BX.ready(function () {
    var mobileHeader = document.getElementById('mobileHeader');
    var mobileFooter = document.getElementById('mobileFooter');
    var pageContent = document.getElementById('pageContent');
    var mobileMenu = document.getElementById('mobileMenu');

    document.getElementById('menuToggle').addEventListener('click', function () {
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
});
