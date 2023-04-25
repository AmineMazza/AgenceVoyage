let detination = document.querySelector(".OffreDestination");
let hotels = document.querySelector("#hotels");

function DesinatioinEvent() {
    let val = detination.options[detination.selectedIndex].text;
    
    hotels.innerHTML = ``;
    let form = hotels.getAttribute('data-prototype');
    if(val == "Omra" || val == "Hajj" || val == "Omra combine"){
        hotels.innerHTML += form;
        hotels.innerHTML += form;
        hotels.innerHTML += form;
    }
    else{
        hotels.innerHTML = hotels.innerHTML += form;
    }
}

DesinatioinEvent();

detination.addEventListener("change",(e)=>{DesinatioinEvent()});