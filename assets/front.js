/****************************************************************************/
/* Home > Collapsable filter */
/****************************************************************************/

let buttonsCollapsible = document.getElementsByClassName('button-collapsible');

for (let i=0; i < buttonsCollapsible.length; i++){
    buttonsCollapsible[i].addEventListener('click', function (){
        this.classList.toggle('active');
        let content = this.nextElementSibling;
        if (content.style.display === 'flex'){
            content.style.display = 'none';
        } else {
            content.style.display = 'flex';
        }
        if (content.style.maxHeight){
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });
}
