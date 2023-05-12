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
const imageOffre=document.getElementById('offre_image');


/******handle image offre */
const newimageInput=document.getElementById("imageInputOffre");
newimageInput.addEventListener('click',function(){
    imageOffre.click();
})
imageOffre.addEventListener('change',function(event){
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        newimageInput.src = e.target.result;
    };
    if (file) {
        reader.readAsDataURL(file);
    }
})


/**************************** */
if(destination.options[destination.selectedIndex].text != "Omra"  || destination.options[destination.selectedIndex].text != "Hajj" || destination.options[destination.selectedIndex].text != "Omra_combine"){
    hotel0.style.display = "block";
    hotel1.style.display = "none";
    hotel2.style.display = "none";
    hbar0.style.display = 'none';
    hbar1.style.display = 'none';
}

destination.addEventListener("change",function(){
    let destVal = destination.options[destination.selectedIndex].text;
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
    if(i==1){
        const title=document.getElementById('offre_titre');
        const titleError=document.getElementById('title-error');
           if(title.value===''){
            titleError.innerText='oblige de remplir titre';
            previousbutton.click();
            previousbutton.style.display = 'none';
            i=1;
           }
           else{
            titleError.innerText='';
            i++;
           }
        }
    else if(i==2){
        const hebergement = document.getElementById('offre_bhebergement');
        const infoHebergementError=document.getElementById('Hebergement');
        if(hebergement.checked == true){
                let destVal = destination.options[destination.selectedIndex].text;
            if(destVal === "Omra"  || destVal === "Hajj" || destVal === "Omra_combine"){
                const hotels_0_name=document.getElementById('offre_hotels_0_name');
                const hotels_0_lieu=document.getElementById('offre_hotels_0_lieu');
                const hotels_0_distance=document.getElementById('offre_hotels_0_distance');
                const hotels_0_nuits=document.getElementById('offre_hotels_0_nombre_nuits');
                const hotels_0_etoile=document.getElementById('offre_hotels_0_etoile');
                const hotels_1_name=document.getElementById('offre_hotels_1_name');
                const hotels_1_lieu=document.getElementById('offre_hotels_1_lieu');
                const hotels_1_distance=document.getElementById('offre_hotels_1_distance');
                const hotels_1_nuits=document.getElementById('offre_hotels_1_nombre_nuits');
                const hotels_1_etoile=document.getElementById('offre_hotels_1_etoile');
                    if((hotels_0_name.value==='' || hotels_0_lieu.value==='' || hotels_0_distance.value==='' || hotels_0_nuits.value==='' || hotels_0_etoile.value==='') || (hotels_1_name.value==='' || hotels_1_lieu.value==='' || hotels_1_distance.value==='' || hotels_1_nuits.value==='' || hotels_1_etoile.value==='')){
                    infoHebergementError.innerText='oblige de remplir tous les champs (minimum des hotile)';
                    previousbutton.click();
                    i=2;
                    }
                    else{
                        infoHebergementError.innerText='';
                        i++;
                    }
            }
            else {
                    const hotels_0_name=document.getElementById('offre_hotels_0_name');
                    const hotels_0_lieu=document.getElementById('offre_hotels_0_lieu');
                    const hotels_0_distance=document.getElementById('offre_hotels_0_distance');
                    const hotels_0_nuits=document.getElementById('offre_hotels_0_nombre_nuits');
                    const hotels_0_etoile=document.getElementById('offre_hotels_0_etoile');
                    const infoHebergementError=document.getElementById('Hebergement');
                        if(hotels_0_name.value==='' || hotels_0_lieu.value==='' || hotels_0_distance.value==='' || hotels_0_nuits.value==='' || hotels_0_etoile.value===''){
                        infoHebergementError.innerText='oblige de remplir tous les champs';
                        previousbutton.click();
                        i=2;
                        }
                        else{
                            infoHebergementError.innerText='';
                            i++;
                        }
            }
    }
    else{
        infoHebergementError.innerText='';
        i++;
    }
    }
    else if(i==3){
        let retour = document.getElementById('offre_baller_retour');
          //date twig error
         const DateError=document.getElementById('DateError');
        if(retour.checked == true){
        const DateYearAller =document.getElementById('offre_date_depart_date_year');
        const DateMonthAller =document.getElementById('offre_date_depart_date_month');
        const DateDayAller =document.getElementById('offre_date_depart_date_day');
        const dateDepart=new Date(DateYearAller.value,DateMonthAller.value,DateDayAller.value);
        const dateCurrent = new Date();
            //date retour
        const DateYearRetour =document.getElementById('offre_date_retour_date_year');
        const DateMonthRetour =document.getElementById('offre_date_retour_date_month');
         const DateDayRetour =document.getElementById('offre_date_retour_date_day');
        const dateRetour=new Date(DateYearRetour.value,DateMonthRetour.value,DateDayRetour.value);
        if(dateDepart.getTime()<dateCurrent.getTime()){
            DateError.innerText='error choisir un date valide';
                 previousbutton.click();
                 i=3;
        }
        else if(dateDepart.getTime()>dateRetour.getTime() || dateRetour.getTime<dateCurrent.getTime()){
            DateError.innerText='error choisir un date retour valide';
            previousbutton.click();
            i=3;
        }
        else{
            DateError.innerText='';
            i++;
        }
    }
    else{
        const DateYearAller =document.getElementById('offre_date_depart_date_year');
        const DateMonthAller =document.getElementById('offre_date_depart_date_month');
        const DateDayAller =document.getElementById('offre_date_depart_date_day');
        const dateDepart=new Date(DateYearAller.value,DateMonthAller.value,DateDayAller.value);
        const dateCurrent = new Date();
        if(dateDepart.getTime()<dateCurrent.getTime()){
            DateError.innerText='error choisir un date valide';
                 previousbutton.click();
                 i=3;
        }
        else{
            DateError.innerText='';
            i++;
        }
    }

    }
    else if(i==4){
        const chambre1=document.getElementById('offre_prix_chambre');
        const chambre2=document.getElementById('offre_prix_chambre_double');
        const chambre3=document.getElementById('offre_prix_chambre_triple');
        const chambre4=document.getElementById('offre_prix_chambre_quad');
        const chambre5=document.getElementById('offre_prix_chambre_quint');
        const ChambreError=document.getElementById('ChambreError');
           if(chambre1.value==='' && chambre2.value==='' && chambre3.value==='' && chambre4.value==='' && chambre5.value ==='' ){
            ChambreError.innerText='oblige de remplir au minimum un champ';
            previousbutton.click();
            i=4;
           }
           else{
            ChambreError.innerText='';
            i++;
           }
    }
    else if(i==5){
        const offrPassport=document.getElementById('offre_bpassport');
        const offreVisa=document.getElementById('offre_bvisa');
        const ErrorVisa=document.getElementById('ErrorVisa');
        if(offrPassport.checked==false || offreVisa.checked==false){
            ErrorVisa.innerText='visa passeport obligatoire';
            previousbutton.click();
            i=5;
        }
        else{
            ErrorVisa.innerText='';
            i++;
        }
    }
    else if(i==8){
        const offreprix=document.getElementById('offre_prix');
        const ErrorPrix=document.getElementById('ErrorPrix');
        if(offreprix.value===''){
            ErrorPrix.innerText='prix obligatoire';
            previousbutton.click();
            i=8;
        }
        else{
            ErrorPrix.innerText='';
            i++;  
        }
    }
    else{
        i++;
    }
    if(i >= 9)
        nextbutton.style.display = 'none' ;
    else if(i<=1){
        previousbutton.style.display = 'none';
    }
    else{
        nextbutton.style.display = 'block';
        previousbutton.style.display = 'block';
    }
})
previousbutton.addEventListener("click",function(){
    i--;
    if(i <= 1){
        previousbutton.style.display = 'none' ;
        nextbutton.disabled=false;
    }
    else{
        previousbutton.style.display = 'block' ;
        nextbutton.style.display = 'block' ;
        nextbutton.disabled=false;
    }
})

// verification input
