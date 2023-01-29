function toggleTheme() {
    var html = document.querySelector("html");
    var theme = html.getAttribute("data-bs-theme");
    if (theme === "light") {
        html.setAttribute("data-bs-theme", "dark");
        localStorage.setItem("theme", "dark");
    } else {
        html.setAttribute("data-bs-theme", "light");
        localStorage.setItem("theme", "light");
    }
}

    window.onload = function() {
        var theme = localStorage.getItem("theme");
        if (theme) {
            document.querySelector("html").setAttribute("data-bs-theme", theme);
            document.querySelector("#flexSwitchCheckDefault").checked = theme === "dark";
        }
    };
