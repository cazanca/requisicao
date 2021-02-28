const searchIcon = document.querySelector('#searchIcon');
const toggleMenu = document.querySelector('#toggle-menu');
const closeMenu = document.querySelector('.close-menu');
const sidebar = document.querySelector('.sidebar');
const link = document.querySelectorAll('.menu-itens a');

const open = document.querySelectorAll('[data-open]');
const close = document.querySelectorAll('[data-close]');
const openArr = Array.prototype.slice.call(open);

// Add active to links
function activeLink() {
    if (link) {
        link.forEach(l => l.classList.remove('active'))
        this.classList.add('active')
    }
}

link.forEach(l => l.addEventListener('click', activeLink))


toggleMenu.addEventListener('click', () => {
    sidebar.classList.add('show-sidebar')
});

closeMenu.addEventListener('click', () => {
    sidebar.classList.remove('show-sidebar')
});

/**
 * Modal
 */
openArr.forEach(function(current, index, array) {
        
    current.addEventListener('click', function(){
        let modalId = array[index].dataset.open;
        document.getElementById(modalId).classList.add('is-visible')
    })
       
});

// Close Modal
for(const el of close){
    el.addEventListener('click', function () {
        this.parentElement.parentElement.parentElement.parentElement.classList.remove('is-visible')
    })
}

document.addEventListener('click', e => {
    if(e.target == document.querySelector('.modal.is-visible')){
        document.querySelector(".modal.is-visible").classList.remove('is-visible')
    }
})

document.addEventListener('keyup', e => {
    if(e.key == "Escape" && document.querySelector('.modal.is-visible')){
        document.querySelector(".modal.is-visible").classList.remove('is-visible')
    }
})
