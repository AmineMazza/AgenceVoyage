let search = document.querySelector("#search-destination");
let distinations = document.querySelectorAll(".distination")


search.addEventListener("input",(e)=>{
    
    distinations.forEach((distination)=>{
        if(e.target.value == ""){
            distination.parentElement.style.display = "inline-flex";
        }
        else if(!distination.innerHTML.toLowerCase().includes(e.target.value.toLowerCase())){
            distination.parentElement.style.display = "none";
        }
    })
})