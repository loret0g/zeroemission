document.addEventListener("DOMContentLoaded", () => {
  const flashMessage = document.getElementById('flashMessage');
  if (flashMessage) {
    setTimeout(() => {
      flashMessage.classList.add('flash-message--hide');
    }, 5000);
  }
});