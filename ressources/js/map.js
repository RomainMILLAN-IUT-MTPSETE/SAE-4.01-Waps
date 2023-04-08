var map = L.map('map').setView([46.904266, 1.868070], 5);
const form = document.querySelector('form');

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

form.addEventListener(`submit`, (e) => {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
});