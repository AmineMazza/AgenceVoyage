let isCommercial=  document.querySelector("#isCommercial")
let select = document.querySelector("#SelectMethod");
select.style.display = "none";
isCommercial.disabled = true;

if(isCommercial.checked){
    document.querySelector('.commercial-info').style.display = "block"; 
}
else{
    document.querySelector('.commercial-info').style.display = "none"; 
}