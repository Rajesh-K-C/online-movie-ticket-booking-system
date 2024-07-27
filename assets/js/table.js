import Query from "./query";

(function(){
    const table = Query('.table');
    let isMouseDown = false;
    let startX;
    let scrollLeft;
    
    table.addEventListener('mousedown', (e) => {
        isMouseDown = true;
        startX = e.pageX - table.offsetLeft;
        scrollLeft = table.scrollLeft;
    });
    
    table.addEventListener('mouseup', () => {
        isMouseDown = false;
    });
    
    table.addEventListener('mouseleave', () => {
        isMouseDown = false;
    });
    
    table.addEventListener('mousemove', (e) => {
        if (!isMouseDown) return;
        e.preventDefault();
        const x = e.pageX - table.offsetLeft;
        const walk = (x - startX) * 2;
        table.scrollLeft = scrollLeft - walk;
    });
})();