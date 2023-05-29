console.log('home'); 
const titleOffres=document.querySelectorAll('.titleOffre');
titleOffres.forEach(title => {
  const originalText = title.textContent;  // Get the original text content

  // Display only the first 10 characters
  const truncatedText = originalText.substring(0, 25);

  // Update the text content of the title
  title.textContent = truncatedText+"...";
});
