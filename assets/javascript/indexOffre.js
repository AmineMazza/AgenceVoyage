const clickableImages = document.querySelectorAll('#clickableImage');
const imageContainer = document.getElementById('displayImage');
const closePop=document.getElementById('ClosePopUp');
const popupContainer=document.getElementById('popup-modal');
clickableImages.forEach((image) => {
  image.addEventListener('click', () => {
    const imageUrl = image.getAttribute('src');
    const newImage = document.createElement('img');
    newImage.setAttribute('src', imageUrl);
    newImage.setAttribute('id', 'imagepop');
    imageContainer.appendChild(newImage);
  });
});

closePop.addEventListener('click',function(){
    const image=document.getElementById('imagepop');
    imageContainer.removeChild(image);
})
popupContainer.addEventListener('click',function(){
    const image=document.getElementById('imagepop');
    imageContainer.removeChild(image);
});

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape" || event.keyCode === 27) {
        const image=document.getElementById('imagepop');
        imageContainer.removeChild(image);
    }
  });