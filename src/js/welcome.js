const welcome = document.querySelector('#welcome')

if(welcome) {
    welcome.addEventListener('click', function() {
        welcome.classList.remove('visible')
    })
}