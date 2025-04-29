document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const filterBtn = document.getElementById("filterBtn");
    const closeBtn = document.getElementById("closeBtn");

    function toggleSidebar() {
         sidebar.classList.toggle("open");//Переключает класс "open" у sidebar.
        // Если класс "open" есть — удаляет его, если нет — добавляет.
    }

    filterBtn.addEventListener("click", toggleSidebar);
    closeBtn.addEventListener("click", toggleSidebar);
});
