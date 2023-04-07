const divAutocompletionDepart = document.getElementById(`autoCompletionDepart`);
const divAutocompletionArrivee = document.getElementById(`autoCompletionArrivee`);
const inputVilleDepart = document.getElementById(`nomCommuneDepart_id`);
const inputVilleArrivee = document.getElementById(`nomCommuneArrivee_id`);
let communes = [];

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
    });
    inputVilleDepart.addEventListener(`input`, (e) => {
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
                            if (inputVilleDepart.value.length >= 2) {
                                afficheVilles(communesSelectionnees, divAutocompletionDepart);
                            }
                        }
                    })
                    .catch(error => console.log(error));
            });
        }else{
            if (inputVilleDepart.value.length >= 2) {
                afficheVilles(communesSelectionnees, divAutocompletionDepart);
            }
        }
    });
}

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

divAutocompletionDepart.addEventListener('click', event => {
    inputVilleDepart.value = event.target.innerHTML;
    videVilles();
});

inputVilleDepart.onkeydown = event => {
    if (event.code === 'ArrowUp') {
        if (0 <= communes.indexOf(inputVilleDepart.value) - 1) {
            inputVilleDepart.value = communes[communes.indexOf(inputVilleDepart.value) - 1]
        } else {
            inputVilleDepart.value = communes[communes.length - 1]
        }
    } else if (event.code === 'ArrowDown') {
        if (communes.indexOf(inputVilleDepart.value) + 1 < communes.length) {
            inputVilleDepart.value = communes[communes.indexOf(inputVilleDepart.value) + 1]
        } else {
            inputVilleDepart.value = communes[0]
        }
    } else if (event.code === 'Enter') {
        videVilles()
    }
    Array.from(divAutocompletionDepart.children).forEach(element => {
        if (element.innerHTML === inputVilleDepart.value) {
            element.style.backgroundColor = "black"
            element.style.color = "white";
        } else {
            element.style.backgroundColor = ""
            element.style.color = "black";
        }
    })
}


/**
 * A faire:
 * - Afficher villes que si la longueur de la value des inputs >= 2
 * - Remplissage de l'input quand l'utilisateur clique sur le champ 
 * - Sélection du champ avec les flèches du clavier (faire avec les keycode === numéroDeLaTouche et pas keycode = 'ArrowDown' ou 'ArrowUp);
 * - La valeur de l'input prend la ville actuelle de l'utilisateur (stockée dans city) lors du clic sur le champ "Votre position" de la div autoCompletion
 */