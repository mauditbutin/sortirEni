const cancelHike = document.getElementById('buttonHikeCancel')


if (cancelHike) {

    /****************************************************************************/
    /* Ouverture / fermeture modale de cancel */
    /****************************************************************************/

    // Ouverture de la modale de cancel

    const modale = document.getElementById('modalCancel');
    cancelHike.addEventListener('click', function () {
        modale.style.display = 'flex';
    })

    //Fermeture de la modale de cancel
    const buttonCloseLocationModale = document.getElementById('buttonCancelModale');
    buttonCloseLocationModale.addEventListener('click', function () {
        modale.style.display = 'none';
    })

    //fermeture si clic à coté
    const modaleLocationOverlay = document.getElementById('modaleCancelOverlay');
    modaleLocationOverlay.addEventListener('click', function () {
        modale.style.display = 'none'
    })
}
