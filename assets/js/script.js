'use strict'
window.addEventListener('DOMContentLoaded', ()=>{
    const burger = document.querySelector(".bars");
    const navLinks = document.querySelector(".nav-links")
    burger.addEventListener('click', ()=>{
        navLinks.classList.toggle("active_Nav");
    })
})