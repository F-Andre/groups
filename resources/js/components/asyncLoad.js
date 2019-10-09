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