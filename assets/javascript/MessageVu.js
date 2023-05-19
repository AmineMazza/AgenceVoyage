const VoirBtn = document.querySelectorAll('#VoirBTN');
const MegContainer=document.getElementById("messagecontainer");
const nameMessage=document.getElementById('nameMessage');
VoirBtn.forEach((btn) => {
    btn.addEventListener('click', function () {
        const id = btn.closest('tr').querySelector('.id-column').textContent;
        const message= btn.closest('tr').querySelector('.message-column').textContent;
        const nom= btn.closest('tr').querySelector('.nom-column').textContent;
        nameMessage.innerHTML=nom;
        MegContainer.innerText=message;
        const numMeg=document.getElementById('numMessage');
        numMeg.innerText=parseInt(numMeg.innerText)-1;
        console.log('Clicked on ID:', id);
    });
});

