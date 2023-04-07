const divAutocompletionArrivee = document.getElementById(`autoCompletionArrivee`);
const divAutocompletionDepart = document.getElementById(`autoCompletionDepart`);
const inputVilleArrivee = document.getElementById(`nomCommuneArrivee_id`);
const inputVilleDepart = document.getElementById(`nomCommuneDepart_id`);
let communesSelectionneesDepart = [];
let communesSelectionneesArrivee = [];
let communes = [];


let xhr = new XMLHttpRequest();
xhr.open(`GET`, `controleurFrontal.php?controleur=noeudCommune&action=getNomsCommunesJSON`, true);
xhr.onload = function () {
    if (xhr.status === 200) {
        callback(xhr);
    }
};
xhr.send(null);

function callback(xhr) {
    communes = Array.from(JSON.parse(xhr.responseText));

    inputVilleArrivee.addEventListener(`input`, (e) => {
        const value = e.target.value;
        communesSelectionneesArrivee = Array.from(communes).filter((commune) => {
            return commune.toLowerCase().startsWith(value.toLowerCase());
        }).slice(0, 5);
        videVilles(divAutocompletionArrivee);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=${pos.coords.longitude}&lat=${pos.coords.latitude}`)
                    .then(response => response.json())
                    .then(data => {
                        if (communes.includes(data.features[0].properties.city)) {
                            city = data.features[0].properties.city;
                            communesSelectionneesArrivee[0] = city
                            communesSelectionneesArrivee.concat(communesSelectionneesArrivee);
                            if (inputVilleArrivee.value.length >= 2) {
                                afficheVilles(communesSelectionneesArrivee, divAutocompletionArrivee);
                            }
                        }
                    })
                    .catch(error => console.log(error));
            });
        } else {
            if (inputVilleArrivee.value.length >= 2) {
                afficheVilles(communesSelectionneesArrivee, divAutocompletionArrivee);
            }
        }
    });


    inputVilleDepart.addEventListener(`input`, (e) => {
        const value = e.target.value;
        communesSelectionneesDepart = Array.from(communes).filter((commune) => {
            return commune.toLowerCase().startsWith(value.toLowerCase());
        }).slice(0, 5);
        videVilles(divAutocompletionDepart);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=${pos.coords.longitude}&lat=${pos.coords.latitude}`)
                    .then(response => response.json())
                    .then(data => {
                        if (communes.includes(data.features[0].properties.city)) {
                            city = data.features[0].properties.city;
                            communesSelectionneesDepart[0] = city
                            communesSelectionneesDepart.concat(communesSelectionneesDepart);
                            if (inputVilleDepart.value.length >= 2) {
                                afficheVilles(communesSelectionneesDepart, divAutocompletionDepart);
                            }
                        }
                    })
                    .catch(error => console.log(error));
            });
        } else {
            if (inputVilleDepart.value.length >= 2) {
                afficheVilles(communesSelectionneesDepart, divAutocompletionDepart);
            }
        }
    });
}

inputVilleDepart.onkeydown = event => {
    if (event.code === 'ArrowUp') {
        if (0 <= communesSelectionneesDepart.indexOf(inputVilleDepart.value) - 1) {
            inputVilleDepart.value = communesSelectionneesDepart[communesSelectionneesDepart.indexOf(inputVilleDepart.value) - 1]
        } else {
            inputVilleDepart.value = communesSelectionneesDepart[communesSelectionneesDepart.length - 1]
        }
    } else if (event.code === 'ArrowDown') {
        if (communesSelectionneesDepart.indexOf(inputVilleDepart.value) + 1 < communesSelectionneesDepart.length) {
            inputVilleDepart.value = communesSelectionneesDepart[communesSelectionneesDepart.indexOf(inputVilleDepart.value) + 1]
        } else {
            inputVilleDepart.value = communesSelectionneesDepart[0]
        }
    } else if (event.code === 'Enter') {
        videVilles(divAutocompletionDepart)
    }
    Array.from(divAutocompletionDepart.children).forEach(element => {
        if (element.innerText === inputVilleDepart.value) {
            element.style.backgroundColor = "grey"
        } else {
            element.style.backgroundColor = ""
        }
    })
}

inputVilleArrivee.onkeydown = event => {
    if (event.code === 'ArrowUp') {
        if (0 <= communesSelectionneesArrivee.indexOf(inputVilleArrivee.value) - 1) {
            inputVilleArrivee.value = communesSelectionneesArrivee[communesSelectionneesArrivee.indexOf(inputVilleArrivee.value) - 1]
        } else {
            inputVilleArrivee.value = communesSelectionneesArrivee[communesSelectionneesArrivee.length - 1]
        }
    } else if (event.code === 'ArrowDown') {
        if (communesSelectionneesArrivee.indexOf(inputVilleArrivee.value) + 1 < communesSelectionneesArrivee.length) {
            inputVilleArrivee.value = communesSelectionneesArrivee[communesSelectionneesArrivee.indexOf(inputVilleArrivee.value) + 1]
        } else {
            inputVilleArrivee.value = communesSelectionneesArrivee[0]
        }
    } else if (event.code === 'Enter') {
        videVilles(divAutocompletionArrivee)
    }
    Array.from(divAutocompletionArrivee.children).forEach(element => {
        if (element.innerText === inputVilleArrivee.value) {
            element.style.backgroundColor = "grey"
        } else {
            element.style.backgroundColor = ""
        }
    })
}

function videVilles(target) {
    target.innerHTML = "";
}

function afficheVilles(villes, target) {
    videVilles(target);
    for (let i = 0; i < villes.length; i++) {
        let p = document.createElement(`p`);
        p.id  = `champ-ville-${i}`;
        p.innerText = villes[i];
        target.appendChild(p);
    }
}

divAutocompletionDepart.addEventListener('click', event => {
    inputVilleDepart.value = event.target.innerHTML;
    videVilles(divAutocompletionDepart);
});

divAutocompletionArrivee.addEventListener('click', event => {
    inputVilleArrivee.value = event.target.innerHTML;
    videVilles(divAutocompletionArrivee);
});

inputVilleArrivee.addEventListener('click', event => {
    inputVilleArrivee.value = event.target.innerHTML;
    videVilles(divAutocompletionDepart);
});

inputVilleDepart.addEventListener('click', event => {
    inputVilleDepart.value = event.target.innerHTML;
    videVilles(divAutocompletionArrivee);
});

/**
 * A faire:
 * - Afficher villes que si la longueur de la value des inputs >= 2
 * - Remplissage de l'input quand l'utilisateur clique sur le champ
 * - Sélection du champ avec les flèches du clavier (faire avec les keycode === numéroDeLaTouche et pas keycode = 'ArrowDown' ou 'ArrowUp);
 * - La valeur de l'input prend la ville actuelle de l'utilisateur (stockée dans city) lors du clic sur le champ "Votre position" de la div autoCompletion
 */