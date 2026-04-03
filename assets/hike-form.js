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
        document.body.classList.add('no-scroll');
    })

    // Fermeture de la modale d'ajout d'un lieu
    const buttonCloseLocationModale = document.getElementById('buttonCloseLocationModale');
    buttonCloseLocationModale.addEventListener('click', function (){
        modale.style.display = 'none';
        document.body.classList.remove('no-scroll');
    })
    const modaleLocationOverlay = document.getElementById('modaleLocationOverlay');
    modaleLocationOverlay.addEventListener('click', function (){
        modale.style.display = 'none';
        document.body.classList.remove('no-scroll');
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
            document.body.classList.remove('no-scroll');

            let dataInfos = await callApi(getLocationInfo + '/' + data.id);
            divAdress.innerText = dataInfos[0].address;
            divZip.innerText = dataInfos[0].city.zipcode;
            divCity.innerText = dataInfos[0].city.name;


        } else {
            // document.querySelectorAll('.form-error').forEach(el => el.innerHTML = '');
            //
            // for (const field in data.errors) {
            //     const input = document.querySelector(`[name$="[${field}]"]`);
            //
            //     if (input) {
            //         const errorDiv = input.closest('.form-widget').nextElementSibling;
            //
            //         errorDiv.innerHTML = data.errors[field].join('<br>');
            //     }
            // }
        }
    });


    /****************************************************************************/
    /* Update dynamique du select des lieux selon ville
    /****************************************************************************/

    const selectCity = document.querySelector('.hike_city_select');
    const selectLocation = document.querySelector('.hike_location_select');

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

    async function updateDisplayAdress(idLocation){
        if (idLocation == null){
            return;
        }
        let data = await callApi(getLocationInfo + '/' + idLocation);
        divAdress.innerText = data[0].address;
        divZip.innerText = data[0].city.zipcode;
        divCity.innerText = data[0].city.name;
    }

    // selectLocation.addEventListener('change', async function(el){
    //     let data = await callApi(getLocationInfo + '/' + el.target.value);
    //     divAdress.innerText = data[0].address;
    //     divZip.innerText = data[0].city.zipcode;
    //     divCity.innerText = data[0].city.name;
    // })

    selectLocation.addEventListener('change', function (el){
        updateDisplayAdress(el.target.value).catch(console.error);
    });

    updateDisplayAdress(selectLocation.value).catch(console.error);

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
