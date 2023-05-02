const nextbutton = document.getElementById('btn1');
const previousbutton = document.getElementById('btn2');
const checkbox = document.querySelector('#offre_baller_retour');
const bar = document.querySelector('#bar');
const retour = document.querySelector('#retour');
const hebergement = document.querySelector('#offre_bhebergement');
const hotels = document.querySelector('#hotels');
const hotel0 = document.querySelector('#offre_hotels_0');
const hotel1 = document.querySelector('#offre_hotels_1');
const hotel2 = document.querySelector('#offre_hotels_2');
const destination = document.querySelector("#offre_id_destination");
const petitdej = document.querySelector("#petitdej");
const hbar0 = document.querySelector("#hbar0");
const hbar1 = document.querySelector("#hbar1");

console.log("norzuz")

if(destination.options[destination.selectedIndex].text != "Omra"  || destination.options[destination.selectedIndex].text != "Hajj" || destination.options[destination.selectedIndex].text != "Omra_combine"){
    hotel0.style.display = "block";
    hotel1.style.display = "none";
    hotel2.style.display = "none";
    hbar0.style.display = 'none';
    hbar1.style.display = 'none';
}

destination.addEventListener("change",function(){
    let destVal = destination.options[destination.selectedIndex].text;
    console.log(destVal);
    if(destVal == "Omra"  || destVal == "Hajj" || destVal == "Omra_combine"){
        hotel0.style.display = 'block' ;
        hotel1.style.display = 'block' ;
        hotel2.style.display = 'block' ;
        hbar0.style.display = 'block' ;
        hbar1.style.display = 'block' ;
    }else{
        hotel1.style.display = 'none';
        hotel2.style.display = 'none';
        hbar0.style.display = 'none';
        hbar1.style.display = 'none';
     }
})

checkbox.checked = true ;
checkbox.addEventListener("change", function(){
    if(checkbox.checked == true){
        bar.style.display = 'block'
        retour.style.display = 'block'
    }else{
        bar.style.display = 'none'
        retour.style.display = 'none'
    }
    
})

hebergement.checked = true ;
hebergement.addEventListener("change", function(){
    if(hebergement.checked == true){
        hotels.style.display = 'block'
        hotels.style.display = 'flex'
        petitdej.style.display = "flex"
    }else{
        hotels.style.display = 'none'
        petitdej.style.display = 'none'
    }
})


previousbutton.style.display = 'none' ;
let i = 1 ;


nextbutton.addEventListener("click",function(){
    i++
    if(i >= 9)
        nextbutton.style.display = 'none' ;
    else{
        nextbutton.style.display = 'block'
        previousbutton.style.display = 'block'
    }
})
previousbutton.addEventListener("click",function(){
    i--
    if(i <= 1)
        previousbutton.style.display = 'none' ;
    else{
        previousbutton.style.display = 'block' ;
        nextbutton.style.display = 'block' ;
    }
})
