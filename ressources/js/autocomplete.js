const divAutocompletionDepart = document.getElementById(`autoCompletionDepart`);
const divAutocompletionArrivee = document.getElementById(`autoCompletionArrivee`);
const inputVilleDepart = document.getElementById(`nomCommuneDepart_id`);
const inputVilleArrivee = document.getElementById(`nomCommuneArrivee_id`);
let currentFocusDepart = 0;
let currentFocusArrivee = 0;

let xhr = new XMLHttpRequest();
xhr.open(`GET`, `controleurFrontal.php?controleur=noeudCommune&action=getNomsCommunesJSON`, true);
xhr.onload = function(){
    if(xhr.status === 200){
        callback(xhr);
    }
};
xhr.send(null);

function callback(xhr){
    communes = Array.from(JSON.parse(xhr.responseText));
    inputVilleArrivee.addEventListener(`input`, (e) => {
        if(inputVilleArrivee.value.length >= 2){
            const value = e.target.value;
            videVilles(divAutocompletionArrivee);
            let communesSelectionnees = Array.from(communes).filter((commune) => {
                return commune.toLowerCase().startsWith(value.toLowerCase());
            }).slice(0, 5);
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition((pos) => {
                    fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=${pos.coords.longitude}&lat=${pos.coords.latitude}`)
                        .then(response => response.json())
                        .then(data => {
                            if(communes.includes(data.features[0].properties.city)){
                                city = data.features[0].properties.city;
                                communesSelectionnees = ["Votre position"].concat(communesSelectionnees);
                                afficheVilles(communesSelectionnees, divAutocompletionArrivee);
                            }
                        })
                        .catch(error => console.log(error));
                });
            }else{
                afficheVilles(communesSelectionnees, divAutocompletionArrivee);
            }
        }else{
            videVilles(divAutocompletionArrivee);
        }
    });
    inputVilleDepart.addEventListener(`input`, (e) => {
        if(inputVilleDepart.value.length >= 2){
            const value = e.target.value;
            videVilles(divAutocompletionDepart);
            let communesSelectionnees = Array.from(communes).filter((commune) => {
                return commune.toLowerCase().startsWith(value.toLowerCase());
            }).slice(0, 5);
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition((pos) => {
                    fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=${pos.coords.longitude}&lat=${pos.coords.latitude}`)
                        .then(response => response.json())
                        .then(data => {
                            if(communes.includes(data.features[0].properties.city)){
                                city = data.features[0].properties.city;
                                communesSelectionnees = ["Votre position"].concat(communesSelectionnees);
                                afficheVilles(communesSelectionnees, divAutocompletionDepart);
                            }
                        })
                        .catch(error => console.log(error));
                });
            }else{
                afficheVilles(communesSelectionnees, divAutocompletionDepart);
            }
        }else{
            videVilles(divAutocompletionDepart);
        }
    });
}

divAutocompletionDepart.addEventListener(`click`, (e) => {
    e.target.innerHTML === `Votre position` ? inputVilleDepart.value = city : inputVilleDepart.value = e.target.innerHTML;
    videVilles(divAutocompletionDepart);
});

divAutocompletionArrivee.addEventListener(`click`, (e) => {
    e.target.innerHTML === `Votre position` ? inputVilleArrivee.value = city : inputVilleArrivee.value = e.target.innerHTML;
    videVilles(divAutocompletionArrivee);
});

function videVilles(target){
    while (target.hasChildNodes()) target.removeChild(target.firstChild);
}

function afficheVilles(villes, target){
    for (let i = 0; i < villes.length; i++) {
        let temp = document.createElement(`p`);
        temp.setAttribute(`id`, `champ-ville-${i}`);
        temp.innerHTML = villes[i];
        target.appendChild(temp);
    }
}

inputVilleDepart.addEventListener(`keydown`, (e) => {
    switch (e.keyCode) {
        case 38:
            if(currentFocusDepart !== 0){
                document.getElementById(`champ-ville-${currentFocusDepart}`).style.backgroundColor = "white";
                currentFocusDepart--;
                document.getElementById(`champ-ville-${currentFocusDepart}`).style.backgroundColor = "#A9BCD0";
            }
            break;
        case 40:
            if(navigator.geolocation){
                if(currentFocusDepart !== 5){
                    document.getElementById(`champ-ville-${currentFocusDepart}`).style.backgroundColor = "white";
                    currentFocusDepart++;
                    document.getElementById(`champ-ville-${currentFocusDepart}`).style.backgroundColor = "#A9BCD0";
                }
            }else {
                if(currentFocusDepart !== 4){
                    document.getElementById(`champ-ville-${currentFocusDepart}`).style.backgroundColor = "white";
                    currentFocusDepart++;
                    document.getElementById(`champ-ville-${currentFocusDepart}`).style.backgroundColor = "#A9BCD0";
                }
            }
            break;
        case 13:
            document.getElementById(`champ-ville-${currentFocusDepart}`).innerText = `Votre position` ? inputVilleDepart.value = city : inputVilleDepart.value = document.getElementById(`champ-ville-${currentFocusDepart}`).innerText;
            videVilles(divAutocompletionDepart);
            break;
        default:
            break;
    }
});

inputVilleArrivee.addEventListener(`keydown`, (e) => {
    console.log("keydown", e);
    switch (e.keyCode) {
        case 38:
            if(currentFocusArrivee !== 0){
                document.getElementById(`champ-ville-${currentFocusArrivee}`).style.backgroundColor = "white";
                currentFocusArrivee--;
                document.getElementById(`champ-ville-${currentFocusArrivee}`).style.backgroundColor = "#A9BCD0";
            }
            break;
        case 40:
            if(navigator.geolocation){
                if(currentFocusArrivee !== 5){
                    document.getElementById(`champ-ville-${currentFocusArrivee}`).style.backgroundColor = "white";
                    currentFocusArrivee++;
                    document.getElementById(`champ-ville-${currentFocusArrivee}`).style.backgroundColor = "#A9BCD0";
                }
            }else {
                if(currentFocusArrivee !== 4){
                    document.getElementById(`champ-ville-${currentFocusArrivee}`).style.backgroundColor = "white";
                    currentFocusArrivee++;
                    document.getElementById(`champ-ville-${currentFocusArrivee}`).style.backgroundColor = "#A9BCD0";
                }
            }
            break;
        case 13:
            document.getElementById(`champ-ville-${currentFocusArrivee}`).innerText = `Votre position` ? inputVilleArrivee.value = city : inputVilleArrivee.value = document.getElementById(`champ-ville-${currentFocusArrivee}`).innerText;
            videVilles(divAutocompletionArrivee);
            break;
        default:
            break;
    }
});

document.addEventListener(`click`, (e) => {
    if(e.target !== inputVilleArrivee || e.target !== inputVilleDepart || e.target !== divAutocompletionArrivee || e.target !== divAutocompletionDepart){
        if(divAutocompletionArrivee.hasChildNodes) videVilles(divAutocompletionArrivee);
        if(divAutocompletionDepart.hasChildNodes) videVilles(divAutocompletionDepart);
    } 
})

/**
 * A faire:
 * - Afficher villes que si la longueur de la value des inputs >= 2 OK
 * - Remplissage de l'input quand l'utilisateur clique sur le champ OK
 * - Sélection du champ avec les flèches du clavier (faire avec les keycode === numéroDeLaTouche et pas keycode = 'ArrowDown' ou 'ArrowUp); OK
 * - La valeur de l'input prend la ville actuelle de l'utilisateur (stockée dans city) lors du clic sur le champ "Votre position" de la div autoCompletion OK
 * - Fix keycode === 13 rempli input avec position utilisateur
 */