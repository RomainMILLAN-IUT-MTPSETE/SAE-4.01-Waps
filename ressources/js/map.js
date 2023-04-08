var map = L.map('map').setView([46.904266, 1.868070], 5);
const form = document.querySelector('form');
const submitButton = document.getElementById('button');

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

submitButton.addEventListener(`click`, (e) => {
    console.log('click');
    e.preventDefault();

    const nomCommuneDepart = document.getElementById('nomCommuneDepart_id').value;
    const nomCommuneArrivee = document.getElementById('nomCommuneArrivee_id').value;

    const xhr = new XMLHttpRequest();
    xhr.timeout = 300000;
    xhr.open('POST', `controleurFrontal.php?controleur=noeudCommune&action=plusCourtChemin`);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(JSON.parse(xhr.responseText));
        }
    };
    xhr.send(`nomCommuneDepart=${encodeURIComponent(nomCommuneDepart)}&nomCommuneArrivee=${encodeURIComponent(nomCommuneArrivee)}`);
});
