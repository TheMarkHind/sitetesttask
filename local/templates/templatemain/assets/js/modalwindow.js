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