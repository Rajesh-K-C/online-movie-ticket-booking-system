export default function Query(ele, all = false) {
    if (all) {
        return Array.from(document.querySelectorAll(ele));
    } else {
        return document.querySelector(ele);
    }
};