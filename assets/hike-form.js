/**
Formulaire d'ajout de randonnées > Formulaire d'ajout de lieu
-> Chargement automatique du lieu après son ajout
 **/

const locationForm = document.getElementById('locationCreateForm');

if (locationForm){

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

            const modale = document.getElementById('modaleFormLocation');
            modale.style.display = 'none';
        } else {
            alert('Erreur : ' + data.errors);
        }
    });

}
