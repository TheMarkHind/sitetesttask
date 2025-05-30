
; /* Start:"a:4:{s:4:"full";s:62:"/local/templates/templatemain/assets/js/main.js?17485196371448";s:6:"source";s:47:"/local/templates/templatemain/assets/js/main.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
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

/* End */
;
; /* Start:"a:4:{s:4:"full";s:69:"/local/templates/templatemain/assets/js/modalwindow.js?17485302471211";s:6:"source";s:54:"/local/templates/templatemain/assets/js/modalwindow.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
BX.ready(function () {
    const openModalBtn = document.getElementById("openModalBtn");
    const phoneModal = document.getElementById("phoneModal");
    const closeBtn = document.querySelector(".close");
    const phoneInput = document.getElementById("phoneInput");
    const submitBtn = document.getElementById("submitPhone");

    openModalBtn.addEventListener("click", function (event) {
        event.stopPropagation(); // Чтобы не срабатывал закрывающий клик
        phoneModal.classList.remove('hidden');
        phoneModal.classList.add('modal');
    });

    closeBtn.addEventListener("click", function () {
        phoneModal.classList.remove('modal');
        phoneModal.classList.add('hidden');
    });

    window.addEventListener("click", function (event) {
        if (event.target === phoneModal) {
            phoneModal.classList.remove('modal');
            phoneModal.classList.add('hidden');
        }
    });

    submitBtn.addEventListener("click", function () {
        const phoneNumber = phoneInput.value;
        alert(`Номер телефона: ${phoneNumber}`);
        phoneModal.style.display = "none";
    });
});
/* End */
;; /* /local/templates/templatemain/assets/js/main.js?17485196371448*/
; /* /local/templates/templatemain/assets/js/modalwindow.js?17485302471211*/
