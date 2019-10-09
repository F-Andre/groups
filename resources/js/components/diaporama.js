var imgClass = document.querySelectorAll('.image-blog');

imgClass.forEach(img => {
  img.addEventListener('click', function (e) {
    openModal(e.target);
  });
});

function openModal(target) {
  var fond,
    closeBtn,
    main;
  if (!document.querySelector('#modal-fond')) {
    fond = document.createElement('div');
    closeBtn = document.createElement('button');
    main = document.createElement('div');
    main.className = 'main';
    fond.id = 'modal-fond';
    closeBtn.id = 'close-btn';
    fond.appendChild(closeBtn);
    fond.appendChild(main);
    document.body.appendChild(fond);

    setTimeout(function () {
      fond.style.opacity = '100';
    }, 100);
  } else {
    fond = document.querySelector('#modal-fond');
    closeBtn = document.querySelector('#close-btn');
  }

  window.addEventListener('scroll', (e) => {
    e.preventDefault();
  })

  var imgList = document.querySelectorAll('.image-blog');
  var position = parseInt(target.getAttribute('data-pos'));

  for (let i = 0; i < imgList.length; i++) {
    var zoom = document.createElement('img');
    zoom.className = 'modal-img';
    zoom.src = imgList[i].src;
    zoom.setAttribute('data-pos', i);
    if (i === position) {
      zoom.setAttribute('displaying', true);
    }
    main.appendChild(zoom);
  }

  closeBtn.addEventListener('click', closeModal);

  document.addEventListener('keydown', function (e) {
    if (e.defaultPrevented) {
      return;
    }
    switch (e.key) {
      case 'Escape':
        closeModal();
        break;

      default:
        return;
    }
    e.preventDefault();
  }, true);

  diaporama();
}

function closeModal() {
  document.querySelector('#modal-fond').style.opacity = '0';
  var imgList = document.querySelectorAll('.image-blog');
  for (var i = 0; i < imgList.length; i++) {
    imgList[i].removeAttribute('displaying');
  }
  setTimeout(function () {
    document.body.removeChild(document.querySelector('#modal-fond'));
  }, 500);
}

function diaporama() {
  var imgList = document.querySelectorAll('.modal-img');

  if (imgList.length > 1) {

    var caretL = document.createElement('p'),
      caretR = document.createElement('p'),
      ul = document.createElement('ul'),
      imgListLenght = imgList.length;

    caretL.className = 'caretL';
    caretR.className = 'caretR';
    caretL.innerHTML = '<i class="fas fa-chevron-left fa-3x"></i>';
    caretR.innerHTML = '<i class="fas fa-chevron-right fa-3x"></i>';

    ul.className = 'diapoList';

    for (var i = 0; i < imgListLenght; i++) {
      var li = document.createElement('li');
      if (imgList[i].getAttribute('displaying')) {
        li.className = 'diapoPuceSelect';
      } else {
        li.className = 'diapoPuce';
      }
      li.classList.add('diapoPuceListe');
      li.setAttribute('data-puce', i);
      ul.appendChild(li);
    }

    document.querySelector('#modal-fond').appendChild(caretL);
    document.querySelector('#modal-fond').appendChild(caretR);
    document.querySelector('#modal-fond').appendChild(ul);

    slider();
  }

  function slider() {
    var btnLeft = document.querySelector('.caretL');
    var btnRight = document.querySelector('.caretR');

    let diapo = document.querySelector('[displaying = true]');
    let pos = parseInt(diapo.getAttribute('data-pos'));
    let diapos = document.querySelectorAll('.modal-img');

    let puces = document.querySelectorAll('.diapoPuceListe');

    if (pos === 0) {
      btnLeft.style.display = 'none';
    } else if (pos === diapos.length) {
      btnRight.style.display = 'none';
    }

    setTimeout(() => {
      let width = document.querySelector('.main').offsetWidth;
      diapos.forEach(diap => {
        let diapWidth = diap.offsetWidth;
        let pad = (width - diapWidth) / 2;
        diap.style.paddingLeft = '' + pad + 'px';
        diap.style.paddingRight = '' + pad + 'px';

        let transLength = diap.offsetWidth;
        diap.style.transform = 'translateX(-' + (pos * transLength) + 'px)';
      });
    }, 300);

    puces.forEach(puce => {
      puce.addEventListener('click', () => {
        pos = parseInt(puce.getAttribute('data-puce'));
        diapos = document.querySelectorAll('.modal-img');

        for (let i = 0; i < diapos.length; i++) {
          if (parseInt(diapos[i].getAttribute('data-pos')) === pos) {
            diapos[i].setAttribute('displaying', true);
            transLength = diapos[i].offsetWidth;
          } else {
            diapos[i].setAttribute('displaying', false);
          }

          diapos[i].style.transform = 'translateX(-' + (pos * transLength) + 'px)';
        }

        for (let i = 0; i < puces.length; i++) {
          if (parseInt(puces[i].getAttribute('data-puce')) === pos) {
            puces[i].classList.add('diapoPuceSelect');
            puces[i].classList.remove('diapoPuce');
          } else {
            puces[i].classList.add('diapoPuce');
            puces[i].classList.remove('diapoPuceSelect');
          }
        }

        if (pos === 0) {
          btnLeft.style.display = 'none';
          btnRight.style.display = 'initial';
        } else if (pos === diapos.length - 1) {
          btnRight.style.display = 'none';
          btnLeft.style.display = 'initial';
        } else {
          btnLeft.style.display = 'initial';
          btnRight.style.display = 'initial';
        }

      });

    });

    btnLeft.addEventListener('click', () => {
      diapo = document.querySelector('[displaying = true');
      pos = parseInt(diapo.getAttribute('data-pos')) - 1;
      diapos = document.querySelectorAll('.modal-img');
      transLength = diapo.offsetWidth;

      if (diapo.previousElementSibling.tagName === 'IMG') {
        diapo.previousElementSibling.setAttribute('displaying', true);
        diapo.setAttribute('displaying', false);
        diapos.forEach(diap => {
          diap.style.transform = 'translateX(-' + (pos * transLength) + 'px)';
        });

        for (let i = 0; i < puces.length; i++) {
          if (parseInt(puces[i].getAttribute('data-puce')) === pos) {
            puces[i].classList.add('diapoPuceSelect');
            puces[i].classList.remove('diapoPuce');
          } else {
            puces[i].classList.add('diapoPuce');
            puces[i].classList.remove('diapoPuceSelect');
          }
        }

        if (pos === 0) {
          btnLeft.style.display = 'none';
          btnRight.style.display = 'initial';
        } else if (pos === diapos.length - 1) {
          btnRight.style.display = 'none';
          btnLeft.style.display = 'initial';
        } else {
          btnRight.style.display = 'initial';
          btnLeft.style.display = 'initial';
        }
      }
    });

    btnRight.addEventListener('click', () => {
      diapo = document.querySelector('[displaying = true');
      pos = parseInt(diapo.getAttribute('data-pos')) + 1;
      diapos = document.querySelectorAll('.modal-img');
      transLength = diapo.offsetWidth;

      if (diapo.nextElementSibling.tagName === 'IMG') {
        diapo.nextElementSibling.setAttribute('displaying', true);
        diapo.setAttribute('displaying', false);
        diapos.forEach(diap => {
          diap.style.transform = 'translateX(-' + (pos * transLength) + 'px)';
        });

        for (let i = 0; i < puces.length; i++) {
          if (parseInt(puces[i].getAttribute('data-puce')) === pos) {
            puces[i].classList.add('diapoPuceSelect');
            puces[i].classList.remove('diapoPuce');
          } else {
            puces[i].classList.add('diapoPuce');
            puces[i].classList.remove('diapoPuceSelect');
          }
        }

        if (pos === 0) {
          btnLeft.style.display = 'none';
          btnRight.style.display = 'initial';
        } else if (pos === diapos.length - 1) {
          btnRight.style.display = 'none';
          btnLeft.style.display = 'initial';
        } else {
          btnLeft.style.display = 'initial';
          btnRight.style.display = 'initial';
        }
      }
    });
  }
};