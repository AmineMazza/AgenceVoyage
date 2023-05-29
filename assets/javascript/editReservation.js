let isCommercial=  document.querySelector("#isCommercial");
isCommercial.disabled = true;
document.querySelector("#RemplissezBtn").disabled = true;
document.querySelector("#RemplissezBtn").style.display = "none";

if(isCommercial.checked){
    document.querySelector('.commercial-info').style.display = "block"; 
}
else{
    document.querySelector('.commercial-info').style.display = "none"; 
}