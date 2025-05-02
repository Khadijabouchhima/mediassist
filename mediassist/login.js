const loginCard = document.getElementById('login-card');
const signupCard = document.getElementById('signup-card');

const showSignup = document.getElementById('show-signup');
const showLogin = document.getElementById('show-login');

// Function to switch between cards
function showCard(cardToShow, cardToHide) {
  cardToHide.classList.remove('active');
  cardToShow.classList.add('active');
}

// Event listeners to toggle between login/signup forms
showSignup.addEventListener('click', (e) => {
  e.preventDefault();
  showCard(signupCard, loginCard);
});

showLogin.addEventListener('click', (e) => {
  e.preventDefault();
  showCard(loginCard, signupCard);
});
