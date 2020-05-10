const burgerElement = document.querySelector('.burger');
const overlayElement = document.querySelector('.overlay');
function onClick(ev) {
  burgerElement.classList.toggle('clicked');
  overlayElement.classList.toggle('show');
  document.querySelector('.navbar').classList.toggle('show');
  document.querySelector('body').classList.toggle('overflow');
}
document.querySelector('.burger').addEventListener('click', onClick)
document.querySelector('.overlay').addEventListener('click', onClick)
