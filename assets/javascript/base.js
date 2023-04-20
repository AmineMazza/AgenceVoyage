const activeLink = window.location.href;
const home = document.getElementById('home');
const offre = document.getElementById('dropdownHoverButton');
const contact = document.getElementById('contact');

// log the active link to the console
console.log(activeLink);
if(activeLink.startsWith("http://127.0.0.1/home")){
    console.log("heeeeey")
    home.classList.add("text-blue-600");
    offre.classList.add("text-gray-900");
    contact.classList.add("text-gray-900");
}else if(activeLink.startsWith("http://127.0.0.1/offre")){
    console.log("hello")
    offre.classList.add("text-blue-600");
    home.classList.add("text-gray-900");
    contact.classList.add("text-gray-900");
}else if(activeLink == "http://127.0.0.1/contact"){

}