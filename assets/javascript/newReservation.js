let isCommercial = document.querySelector("#isCommercial");
let select = document.querySelector("#SelectMethod");
let label = document.querySelector("#SelectLabel");
let Commercialdiv = document.querySelector('#reservation_commercial');
let commercials =  JSON.parse(document.querySelector("#CommercialData").value.replace('$', ' '));
let numVoyageur = document.querySelector("#num-voyageurs");
let data;
let choicesChambre = `<option value=""></option>`;
let choicesPension = `<option value=""></option>`;

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
    <div id="reservation_voyageurs___name__" class="flex">\
        <div>
            <label for="reservation_voyageurs___name___nom" class="required">Nom</label>
            <input type="text" id="reservation_voyageurs___name___nom" name="reservation[voyageurs][__name__][nom]" required="required" maxlength="50" />
        </div>
        <div>
            <label for="reservation_voyageurs___name___prenom" class="required">Prenom</label>
            <input type="text" id="reservation_voyageurs___name___prenom" name="reservation[voyageurs][__name__][prenom]" required="required" maxlength="50" />
        </div>
        <div>
            <label for="reservation_voyageurs___name___cin">Cin</label>
            <input type="text" id="reservation_voyageurs___name___cin" name="reservation[voyageurs][__name__][cin]" maxlength="20" />
        </div>
        <div>
            <label for="reservation_voyageurs___name___num_passport">Num passport</label>
            <input type="text" id="reservation_voyageurs___name___num_passport" name="reservation[voyageurs][__name__][num_passport]" maxlength="100" />
        </div>
        <div>
            <select id="reservation_voyageurs___name___prix" name="reservation[voyageurs][__name__][prix]">
                ${choicesChambre}
            </select>
        </div>
        <div>
            <select id="reservation_voyageurs___name___pension" name="reservation[voyageurs][__name__][pension]">
                ${choicesPension}
            </select>
        </div>
    </div>
    `;
    let num = parseInt(numVoyageur.value);

    for(i = 0; i<num; i++){
        document.querySelector("#voyageurInfo").innerHTML += voyageurHTML.replace(/__name__/g , i);
    }

}


isCommercialEvent();
numVoyageurEvent();