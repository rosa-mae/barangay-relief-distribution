const body = document.body;
const themeToggle = document.getElementById("themeToggle");

function applyTheme(theme) {
    body.setAttribute("data-theme", theme);

    if (themeToggle) {
        themeToggle.textContent =
            theme === "dark" ? "☀️ Light Mode" : "🌙 Dark Mode";
    }
}

const savedTheme = localStorage.getItem("barangayTheme") || "light";
applyTheme(savedTheme);

if (themeToggle) {
    themeToggle.addEventListener("click", function () {
        const currentTheme = body.getAttribute("data-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        applyTheme(newTheme);
        localStorage.setItem("barangayTheme", newTheme);
    });
}
