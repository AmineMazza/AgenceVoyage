const activeLink = window.location.href;
const home = document.querySelector('#home');
const offre = document.querySelector('#dropdownHoverButton');
const contact = document.querySelector('#contact');

// log the active link to the console
console.log(activeLink);
if(activeLink.startsWith("http://127.0.0.1/home")){
    home.classList.add("text-blue-600");
    offre.classList.add("text-gray-900");
    contact.classList.add("text-gray-900");
}else if(activeLink.startsWith("http://127.0.0.1/offre")){
    offre.classList.add("text-blue-600");
    home.classList.add("text-gray-900");
    contact.classList.add("text-gray-900");
}else if(activeLink.startsWith("http://127.0.0.1/contact")){
    console.log("hey1")
    contact.classList.add("text-blue-600");
    offre.classList.add("text-gray-900");
    home.classList.add("text-gray-900");
}