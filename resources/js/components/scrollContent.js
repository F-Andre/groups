var scrollableDivs = document.querySelectorAll('.scrollable-div');

scrollableDivs.forEach(div => {

  let divHeight = parseInt(getComputedStyle(div).getPropertyValue('height'));
  let position = 0;
  let speed = 10;
  let touchesPosStart = 0;

  div.addEventListener('wheel', (e) => {
    e.preventDefault();

    if (position < 0) {
      position = 0;
    }
    if (position > (div.scrollHeight - divHeight)) {
      position = div.scrollHeight - divHeight;
    } else {
      position += (e.deltaY * speed);
      div.scrollTop = position;
    }
  })

  div.addEventListener('touchstart', (e) => {
    touchesPosStart = e.changedTouches[0].pageY;
  })

  div.addEventListener('touchmove', (e) => {
    e.preventDefault();

      if (position < 0) {
        position = 0;
      }
      if (position > (div.scrollHeight - divHeight)) {
        position = div.scrollHeight - divHeight;
      } else {
        position += (touchesPosStart - e.changedTouches[0].pageY) / 7;
        div.scrollTop = position;
      }
    console.log(position + ' | ' + (div.scrollHeight - divHeight));
  }, false)
})