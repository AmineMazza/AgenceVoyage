const VoirBtn = document.querySelectorAll('#VoirBTN');
const MegContainer=document.getElementById("messagecontainer");
const nameMessage=document.getElementById('nameMessage');

VoirBtn.forEach((btn) => {
    btn.addEventListener('click', function () {
        const id = btn.closest('tr').querySelector('.id-column').textContent;
        const message= btn.closest('tr').querySelector('.message-column').textContent;
        const nom= btn.closest('tr').querySelector('.nom-column').textContent;
        const seenMessage=btn.closest('tr').querySelector('.seenMessage-column');
        nameMessage.innerHTML=nom;
        MegContainer.innerText=message;
        const numMeg=document.getElementById('numMessage');
        numMeg.innerText=parseInt(numMeg.innerText)-1;
         // Send AJAX request
         fetch('http://127.0.0.1/api/messages/'+id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({'bstatus':true}),
        })
            .then((response) => response.json())
            .then((result) => {
                seenMessage.innerText='Oui';

                // Perform any additional actions or UI updates as needed
            })
            .catch((error) => {
                console.error('Request failed:', error);
                // Handle error condition
            });
    });
});

