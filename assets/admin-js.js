let btnRando = document.getElementById('admin_btn_tabRando')
let btnUser = document.getElementById('admin_btn_tabUser')
let btnVille = document.getElementById('admin_btn_tabVille')
let btnCampus = document.getElementById('admin_btn_tabCampus')

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
