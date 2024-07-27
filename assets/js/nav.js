
window.addEventListener("resize", () => {
    if (document.body.classList.contains("overflow-hidden") && document.body.offsetWidth > 800) {
        navHideUnhide();
    }
});

function navHideUnhide() {
    document.querySelector('.sidebar').classList.toggle("nav-show");
    document.querySelector('.overlay').classList.toggle("nav-show");
    document.body.classList.toggle("overflow-hidden");
}