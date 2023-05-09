let isCommercial = document.querySelector("#isCommercial");
let select = document.querySelector("#SelectMethod");
let Commercialdiv = document.querySelector('#reservation_commercial');
let commercials =  JSON.parse(document.querySelector("#CommercialData").value.replace('$', ' '));

isCommercial.addEventListener("input",()=>isCommercialEvent());

select.addEventListener("change", ()=>{selectEvent()})

function isCommercialEvent(){
    if(isCommercial.checked){
        select.disabled = false;
        document.querySelector('.commercial-info').style.display = "block";
        select.style.display = "block"
    }
    else{
        select.selectedIndex = 0;
        selectEvent();
        select.disabled = true;
        document.querySelector('.commercial-info').style.display = "none";
        select.style.display = "none"
    }
}

function selectEvent(){
    if(select.options[select.selectedIndex].value == "new"){
        Commercialdiv.innerHTML = `
        <div id="reservation_id_commercial">
            <div>
                <label class="required" for="reservation_id_commercial_nom">Nom</label>
                <input id="reservation_id_commercial_nom" type="text" name="reservation[id_commercial][nom]" required="required" maxlength="50"/>
            </div>
            <div>
                <label class="required" for="reservation_id_commercial_prenom">Prenom</label>
                <input id="reservation_id_commercial_prenom" type="text" name="reservation[id_commercial][prenom]" required="required" maxlength="50"/>
            </div>
            <div>
                <label class="required" for="reservation_id_commercial_telephone">Telephone</label>
                <input id="reservation_id_commercial_telephone" type="text" name="reservation[id_commercial][telephone]" required="required" maxlength="20"/>
            </div>
            <div>
                <label for="reservation_id_commercial_adresse">Adresse</label>
                <input id="reservation_id_commercial_adresse" type="text" name="reservation[id_commercial][adresse]" maxlength="255"/>
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
        <select id="reservation_id_commercial" name="reservation[id_commercial]" class="" required="required">
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