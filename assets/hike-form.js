/**
Formulaire d'ajout de randonnées > Formulaire d'ajout de lieu
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
        const formData = new FormData(locationForm);

        const response = await fetch(locationAjaxPath, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success){
            const select = document.getElementById('hike_create_location');

            const newOption = document.createElement('option');
            newOption.value = data.id;
            newOption.text = data.name;
            newOption.selected = true;

            select.appendChild(newOption);

            modale.style.display = 'none';
        } else {
            alert('Erreur : ' + data.errors);
        }
    });

}
