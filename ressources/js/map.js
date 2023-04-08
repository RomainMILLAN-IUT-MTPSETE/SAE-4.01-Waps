var map = L.map('map').setView([46.904266, 1.868070], 5);
const form = document.querySelector('form');
const inputCommuneDepart = document.getElementById('nomCommuneDepart_id');
const inputCommuneArrivee = document.getElementById('nomCommuneArrivee_id');
const submitButton = document.getElementById('button');
const divResults = document.getElementById('resultat');
let duration;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

submitButton.addEventListener(`click`, (e) => {
    e.preventDefault();
    const nomCommuneDepart = inputCommuneDepart.value;
    const nomCommuneArrivee = inputCommuneArrivee.value;
    if(nomCommuneArrivee.value !== "" && nomCommuneArrivee.value !== ""){
        const xhr = new XMLHttpRequest();
        xhr.timeout = 300000;
        let startTime = new Date();
        xhr.open('POST', `controleurFrontal.php?controleur=noeudCommune&action=plusCourtChemin`);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let endTime = new Date();
                duration = (endTime - startTime) / 1000;
                callbackPCC(xhr);
            }
        };
        xhr.send(`nomCommuneDepart=${encodeURIComponent(nomCommuneDepart)}&nomCommuneArrivee=${encodeURIComponent(nomCommuneArrivee)}`);
    }
});

function callbackPCC(xhr){
    videResults();
    inputCommuneArrivee.value = "";
    inputCommuneDepart.value = "";
    data = JSON.parse(xhr.responseText);
    let p = document.createElement('p');
    p.innerHTML = `Le trajet entre ${data.nomCommuneDepart} et ${data.nomCommuneArrivee} mesure ${(data.distance).toFixed(2)} km.`;
    divResults.appendChild(p);
    let p2 = document.createElement('p');
    p2.innerHTML = `Le calcul du trajet a duré ${duration.toFixed(2)} secondes.`;
    divResults.appendChild(p2);
    let points = [];
    for (let i = 0; i < data.parcours.length; i++) {
        let point = [data.parcours[i].latitude, data.parcours[i].longitude];
        points.push(point);
    }
    let line = L.polyline(points, {color: 'red'}).addTo(map);
}

function videResults(){
    while (divResults.hasChildNodes()) divResults.removeChild(divResults.firstChild);
}
