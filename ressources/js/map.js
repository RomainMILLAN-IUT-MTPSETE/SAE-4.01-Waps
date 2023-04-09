var map = L.map('map').setView([46.904266, 1.868070], 5);
const form = document.querySelector('form');
const inputCommuneDepart = document.getElementById('nomCommuneDepart_id');
const inputCommuneArrivee = document.getElementById('nomCommuneArrivee_id');
const submitButton = document.getElementById('button');
const divResults = document.getElementById('resultat');
let duration;
let trajet;
let markerDepart;
let markerArrivee;

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
    const xhr3 = new XMLHttpRequest();
    xhr3.timeout = 300000;
    xhr3.open('POST', `controleurFrontal.php?controleur=noeudCommune&action=getGid`);
    xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr3.onload = () => {
        if (xhr3.readyState === 4 && xhr3.status === 200) {
            gids = JSON.parse(xhr3.responseText);
            console.log(gids[0]);
            console.log(gids[1]);
            p.innerHTML = `Le trajet entre <a href="controleurFrontal.php?controleur=noeudCommune&action=afficherDetail&gid=${gids[0]}">${data.nomCommuneDepart}</a> et <a href="controleurFrontal.php?controleur=noeudCommune&action=afficherDetail&gid=${gids[1]}">${data.nomCommuneArrivee}</a> mesure ${(data.distance).toFixed(2)} km.`;
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
                        markerDepart = L.marker([coords[0].latitude, coords[0].longitude]).addTo(map);
                        markerDepart.bindPopup(data.nomCommuneDepart).openPopup();
                        markerArrivee = L.marker([coords[1].latitude, coords[1].longitude]).addTo(map);
                        markerArrivee.bindPopup(data.nomCommuneArrivee).openPopup();
                        route = event.routes[0];
                        trajet = L.polyline(route.coordinates, { color: 'purple' }).addTo(map);
                        let bounds = L.latLngBounds(route.coordinates);
                        map.fitBounds(bounds);
                    });
                    map.removeControl(routingControl);
                }
            };
            xhr2.send(`nomCommuneDepart=${encodeURIComponent(data.nomCommuneDepart)}&nomCommuneArrivee=${encodeURIComponent(data.nomCommuneArrivee)}`);
        }
    }
    xhr3.send(`nomCommuneDepart=${encodeURIComponent(data.nomCommuneDepart)}&nomCommuneArrivee=${encodeURIComponent(data.nomCommuneArrivee)}`);
}

function videCarte() {
    if (trajet) trajet.remove();
    if (markerDepart) map.removeLayer(markerDepart);
    if (markerArrivee) map.removeLayer(markerArrivee);
}

function videResults() {
    while (divResults.hasChildNodes()) divResults.removeChild(divResults.firstChild);
}
