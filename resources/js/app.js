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
  let posts = document.querySelectorAll('.post .card-text');
  let postsLength = posts.length;
  for (let i = 0; i < postsLength; i++) {
    let postVh = posts[i].clientHeight / window.innerHeight;
    if (postVh >= 0.4) {
      posts[i].style.overflowY = 'scroll';
    }
  }
}

if (document.getElementsByClassName('comments-div')) {
  let comments = document.getElementsByClassName('comments-div');
  let commentsLength = comments.length;
  for (let i = 0; i < commentsLength; i++) {
    let commentsVh = comments[i].clientHeight / window.innerHeight;
    if (commentsVh >= 0.4) {
      comments[i].style.overflowY = 'scroll';
    }
  }
}