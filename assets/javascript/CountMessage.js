
fetch('/countmessage')
    .then(response => response.json())
    .then(data => {
        let message = data.message;
        const numMeg=document.getElementById('numMessage');
        numMeg.innerText=message[1];
    }).catch(error => {
        console.error('Error:', error);
    });

