/***********************************************************************************/
/***********************************************************************************/
/** Formulaire d'ajout de randonnées + Formulaire d'ajout de lieu
> Mise à jour du select de lieux après avoir sélectionné une ville
> Ajout de lieu dans une modale + changement dynamique après validation
> Maj dynamique de l'adresse en sélectionnant un lieu **/
/***********************************************************************************/
/***********************************************************************************/


const hikeForm = document.getElementById('hikeCreateForm');
const divAdress = document.getElementById('adressLocationHikeCreate');
const divZip = document.getElementById('zipLocationHikeCreate');
const divCity = document.getElementById('cityLocationHikeCreate');

if (hikeForm){

    /****************************************************************************/
    /* Ouverture / fermeture modale d'ajout d'un lieu */
    /****************************************************************************/

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


    /****************************************************************************/
    /* Validation modale d'ajout d'un lieu */
    /****************************************************************************/

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

            let dataInfos = await callApi(getLocationInfo + '/' + data.id);
            divAdress.innerText = dataInfos[0].address;
            divZip.innerText = dataInfos[0].city.zipcode;
            divCity.innerText = dataInfos[0].city.name;


        } else {
            alert('Erreur : ' + data.errors);
        }
    });


    /****************************************************************************/
    /* Update dynamique du select des lieux selon ville
    /****************************************************************************/

    const selectCity = document.getElementById('hike_create_city');
    const selectLocation = document.getElementById('hike_create_location');

    selectCity.addEventListener('change', async function (el){
        let data = await callApi(getLocationsByCity + '/' + el.target.value);
        selectLocation.length = 0;
        createPlaceholder(selectLocation,'Sélectionnez un lieu');
        for(let el of data){
            let newOption = document.createElement('option');
            newOption.value = el.id;
            newOption.text = el.name;
            selectLocation.appendChild(newOption);
        }
    })


    /****************************************************************************/
    /* Mise à jour dynamique de l'adresse selon lieu choisi */
    /****************************************************************************/

    selectLocation.addEventListener('change', async function(el){
        let data = await callApi(getLocationInfo + '/' + el.target.value);
        divAdress.innerText = data[0].address;
        divZip.innerText = data[0].city.zipcode;
        divCity.innerText = data[0].city.name;
    })

}

async function callApi(url) {
    const response = await fetch(url);
    if (response.ok) {
        return response.json();
    }
}

function createPlaceholder(select, innertext){
    let placeholder = document.createElement('option');
    placeholder.text = innertext;
    placeholder.selected = true;
    placeholder.disabled = true;
    placeholder.value = '';
    select.appendChild(placeholder);
}
