let cards = document.getElementsByClassName('card-group');

for (let i = 0; i < cards.length; i++) {
  cards[i].addEventListener('click', () => {
    let link = cards[i].firstElementChild;
    link.click();
  })
};