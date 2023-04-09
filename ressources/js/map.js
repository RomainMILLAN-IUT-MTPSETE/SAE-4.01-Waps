var map = L.map('map').setView([46.904266, 1.868070], 5);
const form = document.querySelector('form');
const inputCommuneDepart = document.getElementById('nomCommuneDepart_id');
const inputCommuneArrivee = document.getElementById('nomCommuneArrivee_id');
const submitButton = document.getElementById('button');
const divResults = document.getElementById('resultat');
let duration;
let route;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

var allLayers = L.layerGroup().addTo(map);

submitButton.addEventListener(`click`, (e) => {
    e.preventDefault();
    const nomCommuneDepart = inputCommuneDepart.value;
    const nomCommuneArrivee = inputCommuneArrivee.value;
    if (nomCommuneArrivee.value !== "" && nomCommuneArrivee.value !== "") {
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

function callbackPCC(xhr) {
    videResults();
    videCarte();
    inputCommuneArrivee.value = "";
    inputCommuneDepart.value = "";
    data = JSON.parse(xhr.responseText);
    let p = document.createElement('p');
    p.innerHTML = `Le trajet entre ${data.nomCommuneDepart} et ${data.nomCommuneArrivee} mesure ${(data.distance).toFixed(2)} km.`;
    divResults.appendChild(p);
    let p2 = document.createElement('p');
    p2.innerHTML = `Le calcul du trajet a duré ${duration.toFixed(2)} secondes.`;
    divResults.appendChild(p2);
    let coords;
    const xhr2 = new XMLHttpRequest();
    xhr2.timeout = 300000;
    xhr2.open('POST', `controleurFrontal.php?controleur=noeudCommune&action=getLatitudeLongitude`);
    xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr2.onload = () => {
        if (xhr2.readyState === 4 && xhr2.status === 200) {
            coords = JSON.parse(xhr2.responseText);
            let routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(coords[0].latitude, coords[0].longitude),
                    L.latLng(coords[1].latitude, coords[1].longitude)
                ],
                routeWhileDragging: true,
                show: false
            }).addTo(map);
            routingControl.on('routesfound', function (event) {
                route = event.routes[0];
                L.polyline(route.coordinates, { color: 'purple' }).addTo(map);
                let bounds = L.latLngBounds(route.coordinates);
                map.fitBounds(bounds);
                routingControl.remove();
            });
        }
    };
    xhr2.send(`nomCommuneDepart=${encodeURIComponent(data.nomCommuneDepart)}&nomCommuneArrivee=${encodeURIComponent(data.nomCommuneArrivee)}`);
}

function videCarte() {
    if(route) route.remove();
}

function videResults() {
    while (divResults.hasChildNodes()) divResults.removeChild(divResults.firstChild);
}
