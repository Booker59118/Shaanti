const btn = document.querySelector('.btn-icone');

btn.addEventListener('click', ()=>{
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth"
    })
})