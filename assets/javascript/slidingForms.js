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
const offrebdemipension=document.getElementById('offre_bdemi_pension');
const offrePrix_demi_pension=document.getElementById('Offre_prix_demi_pension');
const offreDetail_demi_pension=document.getElementById('Offre_detail_demi_pension');
const offreBpension_complete=document.getElementById('offre_bpension_complete');
const offre_prix_complete_pension=document.getElementById('Offre_prix_complete_pension');
const offre_detail_complete_pension=document.getElementById('Offre_detail_complete_pension');
const offreBvisite_medine=document.getElementById('offre_bvisite_medine');
let  dateDepart=0;
let Generatetitle='';
// handle create offre for agent
const foragent = document.querySelector("#forAgent");
const usersinput = document.querySelector("#users-input");
foragent.addEventListener("change",()=>{
    if(foragent.checked) usersinput.style.display = 'block';
    else{
        usersinput.selectedIndex = 0;
        usersinput.style.display = 'none';
    }
})

// handle pension

offrebdemipension.checked = true ;
offrebdemipension.addEventListener('click',function(){
    if(offrebdemipension.checked){
         offreDetail_demi_pension.style.display= "block";
         offrePrix_demi_pension.style.display= "block";
     }
     else{
         offreDetail_demi_pension.style.display= "none";
         offrePrix_demi_pension.style.display= "none";
     }
})

offreBpension_complete.checked = true ;
offreBpension_complete.addEventListener('click',function(){
    if(offreBpension_complete.checked){
         offre_prix_complete_pension.style.display= "block";
         offre_detail_complete_pension.style.display= "block";
     }
     else{
         offre_prix_complete_pension.style.display= "none";
         offre_detail_complete_pension.style.display= "none";
     }
})


/******handle image offre */
const newimageInput=document.getElementById("imageInputOffre");
const errorImageOffre=document.getElementById('errorImageOffre');
newimageInput.addEventListener('click',function(){
    imageOffre.click();
})

imageOffre.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    let isFileRead = false;

    reader.onload = (e) => {
        newimageInput.src = e.target.result;
        console.log(newimageInput);
        newimageInput.addEventListener('load', function() {
            const width = newimageInput.width;
            const height = newimageInput.height;
            console.log('Image width:', width);
            console.log('Image height:', height);

            if (width === 1080 && height === 1920) {
                if (!isFileRead) {
                    isFileRead = true;
                    reader.readAsDataURL(file);
                }
            } else {
                // Width and/or height don't match the desired values
                errorImageOffre.innerText = "Error: Size must be 1920x1080";
              
            }
        });
    };

    if (file) {
        reader.readAsDataURL(file);
    }
});


/**************************** */
// if(destination.options[destination.selectedIndex].text != "Omra"  || destination.options[destination.selectedIndex].text != "Hajj" || destination.options[destination.selectedIndex].text != "Omra_combine"){
//     hotel0.style.display = "block";
//     hotel1.style.display = "none";
//     hotel2.style.display = "none";
//     hbar0.style.display = 'none';
//     hbar1.style.display = 'none';
// }

destination.addEventListener("change",()=>{destinationEvent()})

function destinationEvent(){
    let destVal = destination.options[destination.selectedIndex].text;
    if(destVal == "Omra"  || destVal == "Hajj" || destVal == "Omra_combine"){
        document.querySelector("#visiteMedine").style.display = "flex"
        if(offreBvisite_medine.checked == true){
            document.querySelector("#hbar1").style.display = "block";
            hotel2.style.display = "block";
        }
        else{
            document.querySelector("#hbar1").style.display = "none";
            hotel2.style.display = 'none' ;
        }
        hotel0.style.display = 'block' ;
        hotel1.style.display = 'block' ;
        hbar0.style.display = 'block' ;
    }else{
        offreBvisite_medine.checked = false;
        document.querySelector("#visiteMedine").style.display = "none"
        hotel1.style.display = 'none';
        hotel2.style.display = 'none';
        hbar0.style.display = 'none';
        hbar1.style.display = 'none';
    }
}
destinationEvent();

/*** */

offreBvisite_medine.addEventListener('change',function(){
    if(offreBvisite_medine.checked==true){
        console.log(hotel2);
        document.querySelector("#hbar1").style.display = "block";
        hotel2.style.display= 'block';
    }
    else{
        document.querySelector("#hbar1").style.display = "none";
        hotel2.style.display= 'none';     
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

offrebdemipension.addEventListener('click',function(){
const offrePrix_demi_pension=document.getElementById('offre_prix_demi_pension');
    if(offrebdemipension.checked===false){
        offrePrix_demi_pension.value='';
    }
});
offreBpension_complete.addEventListener('click',function(){
    const offre_prix_complete_pension=document.getElementById('offre_prix_complete_pension');
        if(offre_prix_complete_pension.checked===false){
            offre_prix_complete_pension.value='';
        }
});

previousbutton.style.display = 'none' ;
let i = 1 ;


nextbutton.addEventListener("click",function(){
    if(i==1){
        const forAgent=document.getElementById('forAgent');
        const errorforAgent=document.getElementById('errorforAgent');
        if(forAgent.checked==true){
            const users_input=document.getElementById('users-input');
            if(users_input.options[users_input.selectedIndex].text===""){
                errorforAgent.innerText="error oblige de choiser un agent";
                previousbutton.click();
                previousbutton.style.display = 'none';
                i=1;
            }
            else{
                errorforAgent.innerText='';
                i++;
            }
        }else{
            errorforAgent.innerText='';
              i++;
        }
    }
     else if(i==2){
        const hebergement = document.getElementById('offre_bhebergement');
        const infoHebergementError=document.getElementById('Hebergement');
        let destVal = destination.options[destination.selectedIndex].text;
        if(hebergement.checked == true){
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
            else if(destVal===""){
                infoHebergementError.innerText='oblige de choisir un destination';
                previousbutton.click();
                i=2; 
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
        if(destVal===""){
            infoHebergementError.innerText='oblige de choisir un destination';
            previousbutton.click();
            i=2; 
        }
        else{
        infoHebergementError.innerText='';
        i++;
    }
    }
    }
    else if(i==3){
         const DateError=document.getElementById('DateError');
        const DateYearAller =document.getElementById('offre_date_depart_date_year');
        const DateMonthAller =document.getElementById('offre_date_depart_date_month');
        const DateDayAller =document.getElementById('offre_date_depart_date_day');
        dateDepart=new Date(DateYearAller.value,DateMonthAller.value,DateDayAller.value);
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
    else if(i==4){
        const chambre1=document.getElementById('offre_prix_un');
        const ChambreError=document.getElementById('ChambreError');
           if(chambre1.value==='' ){
            ChambreError.innerText="oblige de remplir Prix par personne d'une personne";
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
        const ErrorVisa=document.getElementById('ErrorVisa');
        if(offrPassport.checked==false){
            ErrorVisa.innerText='passeport obligatoire';
            previousbutton.click();
            i=5;
        }
        else{
            ErrorVisa.innerText='';
            i++;
        }
    }
    else if(i==7){
        const offrePrix_demi_pension=document.getElementById('offre_prix_demi_pension');
        const offre_prix_complete_pension=document.getElementById('offre_prix_complete_pension');
        const ErrorPension=document.getElementById('ErrorPension');
       
        if(offrebdemipension.checked===true && offreBpension_complete.checked === true){
            if(offrePrix_demi_pension.value==='' || offre_prix_complete_pension.value===''){
                ErrorPension.innerText='error prix obligatior';
                previousbutton.click();
                i=7;
            }else{
                ErrorPension.innerText='';
                i++;   
            }

        }
        else if(offrebdemipension.checked===true){
            if(offrePrix_demi_pension.value===''){
                ErrorPension.innerText='error prix obligatior';
                previousbutton.click();
                i=7;
            }
            else{
                ErrorPension.innerText='';
                i++;   
            }
        }
        else if(offreBpension_complete.checked === true){
            if(offre_prix_complete_pension.value===''){
                ErrorPension.innerText='error prix obligatior';
                previousbutton.click();
                i=7;
            }
            else{
                ErrorPension.innerText='';
                i++;   
            }
        }
        else{
            ErrorPension.innerText='';
            i++;
           
        }
    }
    else if(i==8){
               // generate offre titre 
               const chambre1=document.getElementById('offre_prix_un');
               const day = String(dateDepart.getDate()).padStart(2, '0');
               const month = String(dateDepart.getMonth() + 1).padStart(2, '0');  // Month is zero-based, so we add 1
               const year = String(dateDepart.getFullYear()).slice(-2);
               const formattedDate = `${day}/${month}/${year}`;
                const title=document.getElementById('offre_titre');
                Generatetitle='offre a:'+destination.options[destination.selectedIndex].text +'-depart a:'+ formattedDate +'-a partir de:'+chambre1.value;
                console.log(Generatetitle);
                title.value=Generatetitle;
                i++;

     }
      else if(i==9){
        
            const title=document.getElementById('offre_titre');
            const titleError=document.getElementById('title-error');
               if(title.value===''){
                titleError.innerText='oblige de remplir titre';
                previousbutton.click();
                i=9;
               }
               else{
                titleError.innerText='';
                i++;
               }
            }
    else{
        i++;
    }
    if(i >= 10)
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
