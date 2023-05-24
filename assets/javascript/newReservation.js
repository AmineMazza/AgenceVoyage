let isCommercial = document.querySelector("#isCommercial");
let select = document.querySelector("#SelectMethod");
let label = document.querySelector("#SelectLabel");
let Commercialdiv = document.querySelector('#reservation_commercial');
let commercials =  JSON.parse(document.querySelector("#CommercialData").value.replace('$', ' '));
let numVoyageur = document.querySelector("#num-voyageurs");
let data;
let choicesChambre = `<option value=""></option>`;
let choicesPension = `<option value=""></option>`;


let  RemplissezBtn=document.querySelector('.RemplissezBtn');
let reservationRemarque=document.getElementById('reservation_remarque');
let AjoutBtn=document.getElementById('AjoutBtn');
let remplieIsValid=true;
function displayBtn(){
    "text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white";
    "mb-6 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500";
        reservationRemarque.disabled = true;
        reservationRemarque.classList.add("text-gray-400","bg-transparent",'cursor-not-allowed',"hover:bg-gray-200","hover:text-gray-900","rounded-lg","text-sm","p-1.5","ml-auto ","inline-flex","items-center","dark:hover:bg-gray-600", "dark:hover:text-white");
            //** */
        RemplissezBtn.disabled = true;
        RemplissezBtn.style.backgroundColor = "#f2f2f2";
        RemplissezBtn.style.color = "#999999";
        RemplissezBtn.style.cursor = "not-allowed";
         //** */
        isCommercial.disabled = true;
        isCommercial.style.backgroundColor = "#f2f2f2";
        isCommercial.style.color = "#999999";
        isCommercial.style.cursor = "not-allowed";
       //** */
       AjoutBtn.disabled = true;
       AjoutBtn.classList.add('text-white','bg-blue-400','dark:bg-blue-500','cursor-not-allowed','font-medium','rounded-lg','text-sm','px-5','py-2.5','text-center');

}
window.addEventListener('load', function() {
let num = parseInt(numVoyageur.value);
if(num <= 0 || isNaN(num) ){
    displayBtn();
}
else{
    reservationRemarque.disabled = false;
    reservationRemarque.style.backgroundColor = "";
    reservationRemarque.style.color = "";
    reservationRemarque.style.cursor = "";
    RemplissezBtn.disabled = false;
    RemplissezBtn.style.backgroundColor = "";
    RemplissezBtn.style.color = "";
    RemplissezBtn.style.cursor = "";
    isCommercial.disabled = false;
    isCommercial.style.backgroundColor = "";
    isCommercial.style.color = "";
    isCommercial.style.cursor = "";
    reservationRemarque.classList.remove("text-gray-400","bg-transparent",'cursor-not-allowed',"hover:bg-gray-200","hover:text-gray-900","rounded-lg","text-sm","p-1.5","ml-auto ","inline-flex","items-center","dark:hover:bg-gray-600", "dark:hover:text-white");

}
});
const closeRemplier=document.getElementById('closeRemplier');
const SauvegarderBtn=document.getElementById('SauvegarderBtn');
let errorReservation=document.getElementById('errorReservation');

SauvegarderBtn.addEventListener('click',function(){
    const reservationInputs=document.querySelectorAll('.reservationInput');
    const reservationInputChampres=document.querySelectorAll('.reservationInputChampre');
    remplieIsValid = true;
    reservationInputs.forEach((reservationinput,index)=>{
        console.log('loop',index);
        if (index === 0 || index === 1 || index === 2 || index === 3){
         if(reservationinput.value === ""){
            console.log('false');
            remplieIsValid = false;
        }
         }
    });
    reservationInputChampres.forEach((reservationInputChampre,index)=>{
        if(index===0 && reservationInputChampre.options[reservationInputChampre.selectedIndex].text===''){
            remplieIsValid=false;
        }
    });

    if(remplieIsValid==true){
        AjoutBtn.disabled = false;
       AjoutBtn.classList.remove('bg-blue-400','dark:bg-blue-500','cursor-not-allowed');
    }
    else{
        errorReservation.innerText = "veuillez remplir les chomp de premier voyageur.";
        AjoutBtn.disabled = true;
        AjoutBtn.classList.add('text-white','bg-blue-400','dark:bg-blue-500','cursor-not-allowed','font-medium','rounded-lg','text-sm','px-5','py-2.5','text-center'); 
    }
});
//** */

const RetournerBtn=document.getElementById('RetournerBtn');
RetournerBtn.addEventListener('click',function(){
    numVoyageur.value=0;
    displayBtn();
    errorReservation.innerHTML='';
});

//** */
closeRemplier.addEventListener('click',function(){
    const reservationInputs=document.querySelectorAll('.reservationInput');
    const reservationInputChampres=document.querySelectorAll('.reservationInputChampre');
    remplieIsValid=true;
    reservationInputs.forEach((reservationinput,index)=>{
        if (index === 0 || index === 1 || index === 2 || index === 3){
            if(reservationinput.value === ""){
               remplieIsValid = false;
           }
            }
    });
    reservationInputChampres.forEach((reservationInputChampre,index)=>{
        if(index===0 && reservationInputChampre.options[reservationInputChampre.selectedIndex].text===''){
            remplieIsValid=false;
        }
    });
    if(remplieIsValid==true){
        AjoutBtn.disabled = false;
       AjoutBtn.classList.remove('bg-blue-400','dark:bg-blue-500','cursor-not-allowed');
    }
    else{
        errorReservation.innerText = "veuillez remplir les chomp de premier voyageur.";
        AjoutBtn.disabled = true;
        AjoutBtn.classList.add('text-white','bg-blue-400','dark:bg-blue-500','cursor-not-allowed','font-medium','rounded-lg','text-sm','px-5','py-2.5','text-center'); 
    }

})

async function getOffre(){
    let linkTab = window.location.href.split('/');
    let idO = linkTab[linkTab.length-2];
    let response;
    response = await fetch('/api/offres/'+idO, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });
    data = await response.json()      
    if('prixUn' in data){
        choicesChambre += `<option value=${data.prixUn}>chambre seul</option>`;
    }
    if('prixDouble' in data){
        choicesChambre += `<option value=${data.prixDouble}>chambre double</option>`;
    }
    if('prixTriple' in data){
        choicesChambre += `<option value=${data.prixTriple}>chambre triple</option>`;
    }
    if('prixQuad' in data){
        choicesChambre += `<option value=${data.prixQuad}>chambre quad</option>`;
    }
    if('prixQuint' in data){
        choicesChambre += `<option value=${data.prixQunit}>chambre quint</option>`;
    }
    if(data.bdemiPension){
        choicesPension += `<option value=${data.prixDemiPension}>Demi pension</option>`;
    }
    if(data.bpensionComplete){
        choicesPension += `<option value=${data.prixCompletePension}>Pension complete</option>`;
    }
}
getOffre()

isCommercial.addEventListener("input",()=>isCommercialEvent());

numVoyageur.addEventListener('input',()=>{numVoyageurEvent()})

function isCommercialEvent(){
    if(isCommercial.checked){
        document.querySelector('.commercial-info').style.display = "block";
        let options = `<option value=""></option>`;
        commercials.forEach(commercial => {
            options+= `
                <option value="${commercial.id}">${commercial.nom} ${commercial.prenom}</option>
            `
        });
        Commercialdiv.innerHTML = `
        <label for="SelectMethod" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selectionner un commercial</label>
        <select id="reservation_id_commercial" name="reservation[id_commercial]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="required">
            ${options}
        </select>
        `; 
    }
    else{
        Commercialdiv.innerHTML = '';
        document.querySelector('.commercial-info').style.display = "none";
    }
}


function numVoyageurEvent(){
    document.querySelector("#voyageurInfo").innerHTML = ``;
    let voyageurHTML = `
    <div id="reservation_voyageurs___name__" class="space-y-3 border rounded-lg p-6 relative z-10 mt-6">\
        <div class="absolute text-sm font-medium text-gray-500 dark:text-gray-400 duration-300 left-4 -top-3 px-2 z-50 bg-white">Voyageur N&deg; </div>
        <div class="relative">
            <input type="text" id="reservation_voyageurs___name___nom" name="reservation[voyageurs][__name__][nom]"  maxlength="50" class="reservationInput block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="reservation_voyageurs___name___nom" class="required absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Nom</label>
        </div>
        <div class="relative">
            <input type="text" id="reservation_voyageurs___name___prenom" name="reservation[voyageurs][__name__][prenom]" maxlength="50" class="reservationInput block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
            <label for="reservation_voyageurs___name___prenom" class="required absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Prenom</label>
        </div>
        <div class="relative">
            <input type="text" id="reservation_voyageurs___name___cin" name="reservation[voyageurs][__name__][cin]" maxlength="20" class="reservationInput block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
            <label for="reservation_voyageurs___name___cin" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Cin</label>
        </div>
        <div class="relative">
            <input type="text" id="reservation_voyageurs___name___num_passport" name="reservation[voyageurs][__name__][num_passport]" maxlength="100" class="reservationInput block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
            <label for="reservation_voyageurs___name___num_passport" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Num passport</label>
        </div>
        <div>
            <label for="reservation_voyageurs___name___prix" class="block mb-0 text-sm font-small text-gray-500 dark:text-white">Choix de chambre:</label>
            <select id="reservation_voyageurs___name___prix" name="reservation[voyageur][__name__][prix]" class="reservationInputChampre bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                ${choicesChambre}
            </select>
        </div>
        <div>
            <label for="reservation_voyageurs___name___pension" class="block mb-0 text-sm font-small text-gray-500 dark:text-white">Choix de pension:</label>
            <select id="reservation_voyageurs___name___pension" name="reservation[voyageur][__name__][pension]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                ${choicesPension}
            </select>
        </div>
    </div>
    `;

    let num = parseInt(numVoyageur.value);
    if(num <= 0 || isNaN(num) ){
        displayBtn();
    }
    else{
        reservationRemarque.disabled = false;
        reservationRemarque.style.backgroundColor = "";
        reservationRemarque.style.color = "";
        reservationRemarque.style.cursor = "";
        RemplissezBtn.disabled = false;
        RemplissezBtn.style.backgroundColor = "";
        RemplissezBtn.style.color = "";
        RemplissezBtn.style.cursor = "";
        isCommercial.disabled = false;
        isCommercial.style.backgroundColor = "";
        isCommercial.style.color = "";
        isCommercial.style.cursor = "";
    }
    for(i = 0; i<num; i++){
        document.querySelector("#voyageurInfo").innerHTML += voyageurHTML.replace(/__name__/g , i);
    }

}


isCommercialEvent();
numVoyageurEvent();

