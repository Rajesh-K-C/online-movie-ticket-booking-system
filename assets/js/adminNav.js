import Query from "./query";
(function (){
    const table = Query('.adminNav');
    let isMouseDownOnAdminNav = false;
    let startX;
    let scrollLeft;
    
    table.addEventListener('mousedown', (e) => {
        isMouseDownOnAdminNav = true;
        startX = e.pageX - table.offsetLeft;
        scrollLeft = table.scrollLeft;
    });
    
    table.addEventListener('mouseup', () => {
        isMouseDownOnAdminNav = false;
    });
    
    table.addEventListener('mouseleave', () => {
        isMouseDownOnAdminNav = false;
    });
    
    table.addEventListener('mousemove', (e) => {
        if (!isMouseDownOnAdminNav) return;
        e.preventDefault();
        const x = e.pageX - table.offsetLeft;
        const walk = (x - startX) * 2;
        table.scrollLeft = scrollLeft - walk;
    });
})();