/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./components/loadModal');
require('./components/postCreate');
require('./components/postEdit');
require('./components/userEdit');
require('./components/commentCreate');
require('./components/groupCreate.js');
require('./components/groupEdit.js');

/***
 * Global JS
 */

function rafScroll() {
  var lastScrollPos = 0,
    ticking = false;

  function scroll() {
    lastScrollPos = window.scrollY;
    requestTicking();
  }

  function requestTicking() {
    if (!ticking) {
      window.requestAnimationFrame(updateScroll);
    }
    ticking = true;
  }

  function updateScroll() {
    ticking = false;
    var currentScrollPos = lastScrollPos;
    asyLoad(currentScrollPos);
  }

  function asyLoad(scrollPos) {
    var asyLoadElmt = document.querySelectorAll('.post');
    for (var i = 0, aLoadElLength = asyLoadElmt.length; i < aLoadElLength; i++) {
      if (asyLoadElmt[i].children[1].childElementCount == 3) {
        let img = asyLoadElmt[i].children[1].children[1].children[0];
        let imgSrc = img.getAttribute('data-src');
        let imgHeight = window.getComputedStyle(asyLoadElmt[i]).getPropertyValue('height');

        if ((scrollPos + window.innerHeight + 10) >= asyLoadElmt[i].offsetTop) {
          img.src = imgSrc;
        }

        if ((scrollPos + window.innerHeight) > (asyLoadElmt[i].offsetTop + (parseInt(imgHeight) / 2))) {
          img.src = imgSrc;
        }
      }
    }
  }

  window.addEventListener('scroll', scroll);
}

function initImg() {
  var asyLoadElmt = document.querySelectorAll('.post');
  for (let i = 0, aLoadElLength = asyLoadElmt.length; i < aLoadElLength; i++) {
    if (asyLoadElmt[i].children[1].childElementCount == 3) {
      let img = asyLoadElmt[i].children[1].children[1].children[0];
      let imgSrc = img.getAttribute('data-src');

      if ((window.innerHeight + 10) >= asyLoadElmt[i].offsetTop) {
        img.src = imgSrc;
      }
    }
  }
}

function groupLink() {
  let cards = document.getElementsByClassName('card-group');

  for (let i = 0; i < cards.length; i++) {
    cards[i].addEventListener('click', () => {
      let link = cards[i].firstElementChild;
      link.click();
    })
  };
}

window.addEventListener('load', function () {
  initImg();
  rafScroll();
  groupLink();
});

