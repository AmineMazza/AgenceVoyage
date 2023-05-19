const VoirBtn = document.querySelectorAll('#VoirBTN');
const MegContainer=document.getElementById("messagecontainer");

VoirBtn.forEach((btn) => {
    btn.addEventListener('click', function () {
        const id = btn.closest('tr').querySelector('.id-column').textContent;
        const message= btn.closest('tr').querySelector('.message-column').textContent;
        MegContainer.innerText=message;
        console.log('Clicked on ID:', id);
    });
});

