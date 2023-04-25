let detination = document.querySelector(".OffreDestination");
let hotels = document.querySelector("#hotels");

function DesinatioinEvent() {
    let val = detination.options[detination.selectedIndex].text;
    
    hotels.innerHTML = ``;
    let html = `
    <div id="offre_hotels___name__">
        <div>
            <label for="offre_hotels___name___lieu" class="">Lieu</label>
            <input type="text" id="offre_hotels___name___lieu" name="offre[hotels][__name__][lieu]" required="required" maxlength="59" />
        </div>
        <div>
            <label for="offre_hotels___name___etoile" class="">Etoile</label>
            <input type="number" id="offre_hotels___name___etoile" name="offre[hotels][__name__][etoile]" required="required" />
        </div>
        <div>
            <label for="offre_hotels___name___distance" class="">Distance</label>
            <input type="text" id="offre_hotels___name___distance" name="offre[hotels][__name__][distance]" required="required" inputmode="decimal" />
        </div>
        <div>
            <label for="offre_hotels___name___nombre_nuits" class="">Nombre nuits</label>
            <input type="number" id="offre_hotels___name___nombre_nuits" name="offre[hotels][__name__][nombre_nuits]" required="required" />
        </div>
    </div>
    `
    console.log(html.replace(/__name__/g,0))
    let form = hotels.getAttribute('data-prototype');
    if(val == "Omra" || val == "Hajj" || val == "Omra combine"){
        hotels.innerHTML += html.replace(/__name__/g,0);
        hotels.innerHTML += html.replace(/__name__/g,1);
        hotels.innerHTML += html.replace(/__name__/g,2);
    }
    else{
        hotels.innerHTML = hotels.innerHTML += html.replace(/__name__/g,0);
    }
}

DesinatioinEvent();

detination.addEventListener("change",(e)=>{DesinatioinEvent()});