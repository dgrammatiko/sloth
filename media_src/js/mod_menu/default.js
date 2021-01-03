const burgerElement = document.querySelector('.burger');
const navCloseElement = document.querySelector('.navClose');
const navElement = document.querySelector('.navbar');
const overlayElement = document.querySelector('.overlay');
const focusableSelectors = ['a[href]', 'area[href]', 'input:not([disabled])', 'select:not([disabled])', 'textarea:not([disabled])', 'button:not([disabled])', 'iframe', 'object', 'embed', '[contenteditable]', '[tabindex]:not([tabindex^="-"])'];
let focusableElements = [];
const KEYCODE = {
  TAB: 9,
  ESC: 27,
  DOWN: 40,
  UP: 38,
  LEFT: 37,
  RIGHT: 39,
};

function onClick() {
  burgerElement.classList.toggle('clicked');
  burgerElement.setAttribute('aria-expanded', burgerElement.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
  overlayElement.classList.toggle('show');
  navElement.classList.toggle('show');
  document.body.classList.toggle('overflow');

  if (burgerElement.getAttribute('aria-expanded') === 'true') {
    navElement.style.display = 'block';

    focusableElements = [].slice.call(navElement.querySelectorAll(focusableSelectors.join()));
    if (focusableElements.length) {
      focusableElements[0].focus();
    }
    navElement.addEventListener('keydown', keyPress);
  } else {
    focusableElements = [];
    navElement.removeEventListener('keydown', keyPress);
    navElement.style.display = 'none';
    burgerElement.focus();
  }
}

function keyPress(e) {
  const focusedIndex = focusableElements.indexOf(document.activeElement);

  // ESC key
  if (e.keyCode === KEYCODE.ESC) {
    navCloseElement.click();
  }

  // TAB key
  if (e.keyCode === KEYCODE.TAB) {
    if (e.shiftKey && (focusedIndex === 0 || focusedIndex === -1)) {
      focusableElements[focusableElements.length - 1].focus();
      e.preventDefault();
    }
    if (!e.shiftKey && focusedIndex === focusableElements.length - 1) {
      focusableElements[0].focus();
      e.preventDefault();
    }
  }

  // LEFT/UP
  if (e.keyCode === KEYCODE.LEFT || e.keyCode === KEYCODE.UP) {
    if (focusedIndex === 0) {
      // Focus last item within modal
      focusableElements[focusableElements.length - 1].focus();
      e.preventDefault();
    } else {
      focusableElements[focusedIndex - 1].focus();
      e.preventDefault();
    }
  }

  // RIGHT/DOWN
  if (e.keyCode === KEYCODE.RIGHT || e.keyCode === KEYCODE.DOWN) {
    if (focusedIndex === (focusableElements.length - 1)) {
      focusableElements[0].focus();
      e.preventDefault();
    } else {
      focusableElements[focusedIndex + 1].focus();
      e.preventDefault();
    }
  }
}

burgerElement.addEventListener('click', onClick);
overlayElement.addEventListener('click', onClick);
navCloseElement.addEventListener('click', onClick);
