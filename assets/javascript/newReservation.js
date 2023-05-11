let isCommercial = document.querySelector("#isCommercial");
let select = document.querySelector("#SelectMethod");
let label = document.querySelector("#SelectLabel");
let Commercialdiv = document.querySelector('#reservation_commercial');
let commercials =  JSON.parse(document.querySelector("#CommercialData").value.replace('$', ' '));

isCommercial.addEventListener("input",()=>isCommercialEvent());

select.addEventListener("change", ()=>{selectEvent()})

function isCommercialEvent(){
    if(isCommercial.checked){
        select.disabled = false;
        document.querySelector('.commercial-info').style.display = "block";
        select.style.display = "block"
        label.style.display = "block"
    }
    else{
        select.selectedIndex = 0;
        selectEvent();
        select.disabled = true;
        document.querySelector('.commercial-info').style.display = "none";
        select.style.display = "none"
        label.style.display = "none"
    }
}

function selectEvent(){
    if(select.options[select.selectedIndex].value == "new"){
        Commercialdiv.innerHTML = `
        <div id="reservation_id_commercial">
            <div class="relative mb-2">
                <input id="reservation_id_commercial_nom" type="text" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" name="reservation[id_commercial][nom]" placeholder=" " required="required" maxlength="50"/>
                <label class="required absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1" for="reservation_id_commercial_nom">Nom du commercial</label>
            </div>
            <div class="relative mb-2">
                <input id="reservation_id_commercial_prenom" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" name="reservation[id_commercial][nom]" placeholder=" " type="text" name="reservation[id_commercial][prenom]" required="required" maxlength="50"/>
                <label class="required absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1" for="reservation_id_commercial_prenom">Prenom du Commercial</label>
            </div>
            <div class="relative mb-2">
                <input id="reservation_id_commercial_telephone" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" name="reservation[id_commercial][nom]" placeholder=" " type="text" name="reservation[id_commercial][prenom]" type="text" name="reservation[id_commercial][telephone]" required="required" maxlength="20"/>
                <label class="required absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1" for="reservation_id_commercial_telephone">Telephone</label>
            </div>
            <div class="relative mb-2">
                <input id="reservation_id_commercial_adresse" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" name="reservation[id_commercial][nom]" placeholder=" " type="text" name="reservation[id_commercial][prenom]" type="text" name="reservation[id_commercial][adresse]" maxlength="255"/>
                <label class="required absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1" for="reservation_id_commercial_adresse">Adresse</label>
            </div>
        </div>
        `;
    }
    else if(select.options[select.selectedIndex].value == 'exist'){
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
    else if(select.options[select.selectedIndex].value == 'null'){
        Commercialdiv.innerHTML = ``;
    }
}

isCommercialEvent();
selectEvent();