// Zoekfunctie
function zoekJacht() {
    const zoekInput = document.getElementById('zoekInput').value.toLowerCase();
    const jachtLijst = document.getElementById('jachtLijst');
    const jachten = jachtLijst.getElementsByTagName('p');
  
    for (let i = 0; i < jachten.length; i++) {
      const jacht = jachten[i].textContent.toLowerCase();
      if (jacht.includes(zoekInput)) {
        jachten[i].style.display = '';
      } else {
        jachten[i].style.display = 'none';
      }
    }
  }
  
  // Selecteer jacht
  let geselecteerd = null;
  
  function selecteerJacht() {
    const jachtLijst = document.getElementById('jachtLijst');
    const jachten = jachtLijst.getElementsByTagName('p');
  
    for (let i = 0; i < jachten.length; i++) {
      if (jachten[i].style.display !== 'none') {
        geselecteerd = jachten[i].textContent;
        break;
      }
    }
  
    if (geselecteerd) {
      document.getElementById('geselecteerdJacht').textContent = `Geselecteerd: ${geselecteerd}`;
      document.getElementById('registratieFormulier').style.display = 'block';
    } else {
      alert('Geen zichtbaar jacht om te selecteren. Zoek een jacht eerst.');
    }
  }
  
  // Formulierverwerking
  document.getElementById('registratieForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Voorkom standaard form-inzending
  
    const naam = document.getElementById('naam').value;
    const email = document.getElementById('email').value;
    const telefoon = document.getElementById('telefoon').value;
  
    alert(`Reservering bevestigd voor:\nNaam: ${naam}\nE-mail: ${email}\nTelefoon: ${telefoon}\nJacht: ${geselecteerd}`);
    document.getElementById('registratieForm').reset();
    document.getElementById('registratieFormulier').style.display = 'none';
  });
  