const themeToggle = document.getElementById("themeToggle");
const htmlElement = document.documentElement;
const iconElement = themeToggle.querySelector("i");

function setTheme(theme) {
  htmlElement.setAttribute("data-bs-theme", theme);
  if (theme === "dark") {
    iconElement.classList.replace("bi-sun-fill", "bi-moon-fill");
  } else {
    iconElement.classList.replace("bi-moon-fill", "bi-sun-fill");
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "light";
  setTheme(savedTheme);
});

themeToggle.addEventListener("click", () => {
  let currentTheme = htmlElement.getAttribute("data-bs-theme");
  let newTheme = currentTheme === "light" ? "dark" : "light";
  setTheme(newTheme);

  localStorage.setItem("theme", newTheme);
});

const sidebarToggle = document.getElementById("sidebarToggle");
const sidebar = document.getElementById("sidebar");
const contentWrapper = document.querySelector(".content-wrapper");

sidebarToggle.addEventListener("click", () => {
sidebar.classList.toggle("show");
});


document.addEventListener("click", (event) => {
const isClickInsideSidebar = sidebar.contains(event.target);
const isClickOnToggle = sidebarToggle.contains(event.target);

if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 768) {
    sidebar.classList.remove("show");
}
});


window.addEventListener("resize", () => {
if (window.innerWidth >= 768) {
    sidebar.classList.remove("show");
}
});