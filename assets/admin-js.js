let btnRando = document.getElementById('admin_btn_tabRando')
let btnUser = document.getElementById('admin_btn_tabUser')
let btnVille = document.getElementById('admin_btn_tabVille')
let btnCampus = document.getElementById('admin_btn_tabCampus')


//Récupération des boutons hikes
let btnsHikes = document.querySelectorAll('.admin_BtnInfosUserRando')


btnVille.addEventListener('click', ChangeCouleurFondVille)
btnUser.addEventListener('click', ChangeCouleurFondUser)
btnCampus.addEventListener('click', ChangeCouleurFondCampus)
btnRando.addEventListener('click', ChangeCouleurFondRando)


function ChangeCouleurFondVille() {
    btnRando.classList.remove('admin_clickedBtn')
    btnUser.classList.remove('admin_clickedBtn')
    btnCampus.classList.remove('admin_clickedBtn')

    if (!btnVille.classList.contains('admin_clickedBtn')) {
        btnVille.classList.add('admin_clickedBtn')
    }
}

function ChangeCouleurFondRando() {
    btnVille.classList.remove('admin_clickedBtn')
    btnUser.classList.remove('admin_clickedBtn')
    btnCampus.classList.remove('admin_clickedBtn')

    if (!btnRando.classList.contains('admin_clickedBtn')) {
        btnRando.classList.add('admin_clickedBtn')
    }
}

function ChangeCouleurFondUser() {
    btnRando.classList.remove('admin_clickedBtn')
    btnVille.classList.remove('admin_clickedBtn')
    btnCampus.classList.remove('admin_clickedBtn')

    if (!btnUser.classList.contains('admin_clickedBtn')) {
        btnUser.classList.add('admin_clickedBtn')
    }
}

function ChangeCouleurFondCampus() {
    btnRando.classList.remove('admin_clickedBtn')
    btnUser.classList.remove('admin_clickedBtn')
    btnVille.classList.remove('admin_clickedBtn')

    if (!btnCampus.classList.contains('admin_clickedBtn')) {
        btnCampus.classList.add('admin_clickedBtn')
    }
}

btnsHikes.forEach(function (btn) {
    btn.addEventListener('click', function () {
        // On récupère l'id du user associé au bouton cliqué
        let userId = btn.dataset.userId

        // On cible uniquement les tr de CE user
        let sections = document.querySelectorAll('.admin_sectionRando[data-user-id="' + userId + '"]')

        sections.forEach(function (section) {
            // toggle affiche/cache
            section.classList.toggle('admin_InfoHide')
            section.classList.toggle('admin_InfoDisplay')

        })
    })
})




