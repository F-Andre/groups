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
require('./components/invitForm.js');
require('./components/diaporama.js');
require('./components/groupLink.js');
require('./components/asyncLoad.js');
require('./components/scrollContent.js');

/***
 * Global JS
 */
if (document.querySelectorAll('.post')) {
  var asyLoadElmt = document.querySelectorAll('.post');
  for (let i = 0, aLoadElLength = asyLoadElmt.length; i < aLoadElLength; i++) {
    if (asyLoadElmt[i].children[1].childElementCount == 3) {
      let img = asyLoadElmt[i].children[1].children[1].children[0];
      let imgSrc = img.getAttribute('data-src');
      if ((parseInt(window.scrollY) + 10) >= asyLoadElmt[i].offsetTop) {
        img.src = imgSrc;
      }
    }
  }
}
