// JavaScript to handle adding medications to the list
const photoInput = document.getElementById("medication-photo");
photoInput.addEventListener("change", function(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const imgPreview = document.createElement("img");
      imgPreview.src = e.target.result;
      imgPreview.style.maxWidth = "150px";
      imgPreview.style.marginTop = "1rem";
      document.getElementById("add-medication-form").appendChild(imgPreview);
    };
    reader.readAsDataURL(file);
  }
});

document.getElementById('add-medication-form').addEventListener('submit', function(event) {
    event.preventDefault();
  
    // Get form values
    const medicationName = document.getElementById('medication-name').value;
    const dosage = document.getElementById('dosage').value;
    const frequency = document.getElementById('frequency').value;
    const time = document.getElementById('time').value;
  
    // Create a new list item for the medication
    const newListItem = document.createElement('li');
    newListItem.innerHTML = `
      <strong>${medicationName}</strong><br>
      Posologie: ${dosage}<br>
      Fréquence: ${frequency}<br>
      Heure de prise: ${time}
    `;
  
    // Append the new list item to the medications list
    document.getElementById('medications-ul').appendChild(newListItem);
  
    // Clear form fields after submission
    document.getElementById('add-medication-form').reset();
  });
  