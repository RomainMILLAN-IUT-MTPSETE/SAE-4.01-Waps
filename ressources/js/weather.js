const apiKey = `b8e14ab0ce63c957c8ddcb5c79a9a25b`;
const nomVille = document.getElementById('nomVille').innerHTML;
const container = document.getElementById('detailCommune-container');

window.onload = () => {
    afficheMeteo();
}

function afficheMeteo() {
	const url = `https://api.openweathermap.org/data/2.5/weather?q=${nomVille}&APPID=${apiKey}&units=metric&lang=fr`;
	let xhr = new XMLHttpRequest();
	xhr.open(`GET`, url, true);
	xhr.onload = () => {
		if(xhr.status === 200 && xhr.readyState === 4) {
			const data = JSON.parse(xhr.responseText);
			const { main, name, sys, weather } = data;
			let meteo = weather[0].description;
			meteo = meteo.charAt(0).toUpperCase() + meteo.slice(1);
			const div = document.createElement(`div`);
			div.innerHTML = `
        		<h2>Ville: ${name}, ${sys.country}</h2>
        		<p>Température: ${Math.round(main.temp)}°C</p>
				<p>Ressenti: ${Math.round(main.feels_like)}°C</p>
				<img src="https://openweathermap.org/img/wn/${weather[0].icon}@2x.png" alt="${meteo}">
				<p>Météo: ${meteo}</p>
				<p>Lever du soleil: ${new Date(sys.sunrise * 1000).toLocaleTimeString('fr-FR')}</p>
				<p>Coucher du soleil: ${new Date(sys.sunset * 1000).toLocaleTimeString('fr-FR')}</p>
      		`;
			container.appendChild(div);
		}else{
			console.log(new Error(xhr.statusText));
		}
	}
	xhr.send();
}
