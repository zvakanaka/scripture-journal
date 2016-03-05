var element = document.querySelector("#greeting");
element.innerText = "Scripture Journal";

var email = document.querySelector("#user-email");
email.value = localStorage.getItem('user-email');

/***************************
 * Check Email
 ***************************/
function checkEmail(email) {
  localStorage.setItem('user-email', email.value);
  console.log('Email is ' + email.value);
  
  //TODO: call database to check email
  var emailConfirm = document.querySelector("#"+email.getAttribute('id')+"-confirm");
  emailConfirm.className = "label label-success";
  emailConfirm.innerHTML = 'Email Found';
  emailConfirm.className = "label label-danger";
  emailConfirm.innerHTML = 'Invalid email';
  
  var userEmail = localStorage.getItem('user-email');
  var action = 'check-email';
	
	var jsonString = {
                      user: userEmail,
                      action: action
                    };

	var stringified = JSON.stringify(jsonString);
	
  //call database to insert
  database(stringified, 'db/web_service.php');
}

/***************************
 * Upload or pull journal data
 * depending on json
 ***************************/
function database(stringified, url) {
  var http = new XMLHttpRequest(); 
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/json; charset=utf-8");
  http.onreadystatechange = function() {
    if (http.readyState == 4 && http.status == 200) {
    	//response
    	var data = (http.responseText);
    	console.log(data);
    	data = JSON.parse(data);
    	var user = data.user;
    	console.log('Received from DB: '+user);
    	for (var i = 0; i < data.journal.length; i++) {
			  var question = data.journal[i].question;
    	}
    }
  };
  http.send(stringified);
}

/***************************
 * COUNT WORDS
 ***************************/
function countWords(idOfBox, numWordsRequired) {
  var box = document.querySelector("#"+idOfBox);
  //console.log('Box content: ' + box.value);
  var words = 1;
  //count words
  for (var i = 0; i < box.value.length; i++) {
    if (box.value[i] == ' ') {
      words++;
    }
  }
  
  var counter = document.querySelector("#"+idOfBox+"-counter");
  counter.innerHTML = words+"/"+numWordsRequired;
  if (words >= numWordsRequired) {  
    counter.style.color = 'green';
  }
}

/***************************
 * COPY
 * Taken from http://www.sitepoint.com/javascript-copy-to-clipboard/
 ***************************/
(function() {

  'use strict';

  // click events
  document.body.addEventListener('click', copy, true);

  // event handler
  function copy(e) {

    // find target element
    var
      t = e.target,
      c = t.dataset.copytarget,
      inp = (c ? document.querySelector(c) : null);

    // is element selectable?
    if (inp && inp.select) {

      // select text
      inp.select();

      try {
        // copy text
        document.execCommand('copy');
        inp.blur();
      }
      catch (err) {
        alert('please press Ctrl/Cmd+C to copy');
      }

    }

  }

})();