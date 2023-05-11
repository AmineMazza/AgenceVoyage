const agentlogo=document.getElementById('agent_logo');
const newimageInput=document.getElementById("imageInput");
newimageInput.addEventListener('click',function(){
    agentlogo.click();
})
agentlogo.addEventListener('change',function(event){
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        newimageInput.src = e.target.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    }
})
