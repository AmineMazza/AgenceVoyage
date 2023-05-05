const clickableImages = document.querySelectorAll('#clickableImage');
const imageContainer = document.getElementById('displayImage');
const closePop=document.getElementById('ClosePopUp');
console.log(closePop);
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
