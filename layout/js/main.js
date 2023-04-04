// Change opacity of background color header when scrolling
document.addEventListener("scroll", () => {

    let nav = document.querySelector("nav");

    this.scrollY > 100 ? nav.style.backgroundColor = "#222222ab" : nav.style.backgroundColor = "#222";

});

// Change the year automatically
document.querySelector("footer .footer p .year").textContent = new Date().getFullYear();

// Toggle menu
let menu  = document.querySelector(".menu"),
    links = document.querySelector(".links");

// Add, remove active class on click
menu.addEventListener("click", () => {

    links.classList.toggle("active");

});

// Stop clicking on all children links
links.addEventListener("click", (e) => {

    e.stopPropagation();

});

// Close the menu when click anywhere
document.addEventListener("click", (e) => {

    if(links.classList.contains("active")) {

        if(e.target !== menu) {

            links.classList.toggle("active");

        }

    }

});

// Scroll to top
let scrollToTop = document.querySelector(".scroll-to-top");

// Show, hide up button when scrolling
window.onscroll = () => { this.scrollY >= 700 ? scrollToTop.classList.add("go-to-up") : scrollToTop.classList.remove("go-to-up"); }

// Go to up after click up button
scrollToTop.onclick = () => { window.scrollTo({top: 0, behavior: "smooth"}); }