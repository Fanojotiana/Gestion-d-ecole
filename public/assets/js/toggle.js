// toggle.js
document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggle_btn");
  const sidebar = document.getElementById("sidebar");
  const mainWrapper = document.querySelector(".main-wrapper");

  toggleBtn.addEventListener("click", function () {
    sidebar.classList.toggle("collapsed");
    mainWrapper.classList.toggle("expanded");
  });
});
