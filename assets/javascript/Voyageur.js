const voyageurID=document.getElementById('voyageurID');
const id=voyageurID.textContent.split("#")[1];
const voyageurPension=document.getElementById('voyageur_pension');
const voyageurChambre=document.getElementById('voyageur_chambre');
function getCookie(cookieName) {
    const cookieValue = document.cookie
      .split('; ')
      .find(cookie => cookie.startsWith(cookieName + '='));
    if (cookieValue) {
      return cookieValue.split('=')[1];
    }
    return null;
  }
  
  // Usage
  let jwtToken = getCookie('jwt_token');
  if (jwtToken) {

    axios.get(`/api/voyageurs/${id}`, {
        headers: {
            'Authorization': `Bearer ${jwtToken}`
        }
    })
        .then(response => {
           let chambre=response.data.chambre; 
           if(chambre==="chambre seul"){
            const optionToSelect = voyageurChambre.querySelector(`option[value="prixUn"]`);
            if (optionToSelect) {
            optionToSelect.selected = true;
            }
           }
           else if(chambre==="chambre double"){
            const optionToSelect = voyageurChambre.querySelector(`option[value="prixDouble"]`);
            if (optionToSelect) {
            optionToSelect.selected = true;
            }
           }
           else if(chambre==="chambre triple"){
            const optionToSelect = voyageurChambre.querySelector(`option[value="prixTriple"]`);
            if (optionToSelect) {
            optionToSelect.selected = true;
            }
           }
           else if(chambre==="chambre quad"){
            const optionToSelect = voyageurChambre.querySelector(`option[value="prixQuad"]`);
            if (optionToSelect) {
            optionToSelect.selected = true;
            }
           }
           else if(chambre==="chambre quint"){
            const optionToSelect = voyageurChambre.querySelector(`option[value="prixQuint"]`);
            if (optionToSelect) {
            optionToSelect.selected = true;
            }
           }
           let pension=response.data.pension; 
           if(voyageurPension){
            if(pension==="demi pension"){
                const optionToSelect2 = voyageurPension.querySelector(`option[value="demiPension"]`);
                if (optionToSelect2) {
                optionToSelect2.selected = true;
                }
            }
            else if(pension==="pension complete"){
                const optionToSelect2 = voyageurPension.querySelector(`option[value="pensionComplete"]`);
                if (optionToSelect2) {
                optionToSelect2.selected = true;
                }
            }
           }
           // Handle the retrieved data here
        })
        .catch(error => {
            console.error('Error:', error);
        });
    

  }
