/**
Formulaire d'ajout de randonnées > Formulaire d'ajout de lieu
-> Mise à jour du select de lieux après avoir sélectionné une ville
-> Chargement automatique du lieu après son ajout
 **/

const hikeForm = document.getElementById('hikeCreateForm');

if (hikeForm){

    // Ouverture de la modale d'ajout d'un lieu
    const buttonOpenLocationModale = document.getElementById('buttonOpenLocationModale');
    const modale = document.getElementById('modaleFormLocation');
    buttonOpenLocationModale.addEventListener('click', function (){
        modale.style.display = 'flex';
    })

    // Fermeture de la modale d'ajout d'un lieu
    const buttonCloseLocationModale = document.getElementById('buttonCloseLocationModale');
    buttonCloseLocationModale.addEventListener('click', function (){
        modale.style.display = 'none';
    })
    const modaleLocationOverlay = document.getElementById('modaleLocationOverlay');
    modaleLocationOverlay.addEventListener('click', function (){
        modale.style.display = 'none'
    })

    // Validation de la modale d'ajout d'un lieu
    const locationForm = document.getElementById('locationCreateForm');
    locationForm.addEventListener('submit', async function (event){
        event.preventDefault();
        let formData = new FormData(locationForm);

        let response = await fetch(locationAjaxPath, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success){
            const select = document.getElementById('hike_create_location');

            let newOption = document.createElement('option');
            newOption.value = data.id;
            newOption.text = data.name;
            newOption.selected = true;

            select.appendChild(newOption);

            modale.style.display = 'none';
        } else {
            alert('Erreur : ' + data.errors);
        }
    });

    // Tri dynamique des locations selon city
    const selectCity = document.getElementById('hike_create_city');
    const selectLocation = document.getElementById('hike_create_location');
    //Path api : getLocationsByCity
    selectCity.addEventListener('change', async function (el){
        let data = await callApi('http://localhost:8081/sortirEni/public/location/byCity/' + el.target.value);
        selectLocation.length = 0;
        for(let el of data){
            let newOption = document.createElement('option');
            newOption.value = el.id;
            newOption.text = el.name;
            selectLocation.appendChild(newOption);
        }
    })

}

async function callApi(url) {
    const response = await fetch(url);
    if (response.ok) {
        return response.json();
    }
}
