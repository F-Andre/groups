let cards = document.getElementsByClassName('group-card-body');

for (let i = 0; i < cards.length; i++) {
  cards[i].addEventListener('click', () => {
    let link = cards[i].firstElementChild;
    link.click();
  })
};