var element = document.querySelector("#greeting");
element.innerText = "Scripture Journal";

//To handle newlines and the like in JSON
//TODO: this should also be happening on the PHP side... security
function jsonEscape(str)  {
    return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
}

/***************************
 * SAVE JOURNAL
 ***************************/
var saveJournal = function() {
  var email = document.querySelector("#user-email").value;
  var thoughts = document.querySelector("#past-thoughts-text").value;
  var ponder = document.querySelector("#ponder-question-text").value;
  var question = document.querySelector("#question-text").value;
  var share = document.querySelector("#share-text").value;
  var promptings = document.querySelector("#promptings-text").value;

  var userEmail = localStorage.getItem('user-email');
  var action = 'insert-entry';
	
	var jsonString = {
                      user: jsonEscape(userEmail),
                      action: jsonEscape(action),
                      thoughts: jsonEscape(thoughts),
                      ponder: jsonEscape(ponder),
                      question: jsonEscape(question),
                      share: jsonEscape(share),
                      promptings: jsonEscape(promptings)
                    };

	var stringified = JSON.stringify(jsonString);
	
  //call database to insert
  database(stringified, 'db/web_service.php');
};

/***************************
 * POPULATE ENTRY
 * When a sidebar entry is
 * clicked on, load it up
***************************/
function populateEntryForm(entry) {
  console.log('POPULATE ENTRY with '+ entry.date + ' and ' + entry.entryId);
  //Call db with user_id and email JSON and get-entry-details action
  var userEmail = localStorage.getItem('user-email');
  var action = 'get-entry-details';
	
	var jsonString = {
                      user: jsonEscape(userEmail),
                      action: action,
                      entryId: entry.entryId
                    };

	var stringified = JSON.stringify(jsonString);
	
  //call database to grab
  getEntryDetails(stringified, 'db/web_service.php');
}

/***************************
 * GET ENTRY DETAILS
 * from database
 ***************************/
function getEntryDetails(stringified, url) {
  var http = new XMLHttpRequest(); 
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/json; charset=utf-8");
  http.onreadystatechange = function() {
    if (http.readyState == 4 && http.status == 200) {
    	//response
    	var data = (http.responseText);
    	console.log(data);
    	data = JSON.parse(data);
    	if (data.error !== undefined) {
    	  console.log('ERROR: ' + data.error);
    	} else {                 //all is well
      	var user = data.user;
      	  
        document.querySelector("#past-thoughts-text").value = data.pastThought;
        document.querySelector("#ponder-question-text").value = data.ponderQuestion;
        document.querySelector("#question-text").value = data.question;
        document.querySelector("#share-text").value = data.share;
        document.querySelector("#promptings-text").value = data.promptings;
  
      	console.log('Received from DB: '+data.user + ', '+data.pastThought);
    	}
    }
  };
  http.send(stringified);
}

/***************************
 * CHECK EMAIL
 ***************************/
var checkEmail = function(email) {
  localStorage.setItem('user-email', email.value);
  console.log('Email is ' + email.value);
  
  //call database to check email later
  var emailConfirm = document.querySelector("#user-email-confirm");
  emailConfirm.className = "button-error pure-button";
  emailConfirm.innerHTML = 'Account Failure';
  
  var userEmail = localStorage.getItem('user-email');
  var action = 'check-email';
	
	var jsonString = {
                      user: jsonEscape(userEmail),
                      action: action
                    };

	var stringified = JSON.stringify(jsonString);
	
  //call database to insert
  database(stringified, 'db/web_service.php');
};

/***************************
 * DATABASE
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
    	if (data.error !== undefined) {
    	  console.log('ERROR: ' + data.error);
    	} else {                 //all is well
      	var user = data.user;
      	
      	var emailConfirm = document.querySelector("#user-email-confirm");
        emailConfirm.className = "button-success pure-button";
        emailConfirm.innerHTML = 'Email Found';
        var submitButton = document.querySelector("#submit-button");
        submitButton.className = "pure-button";//this removes the disabled
        
      	console.log('Received from DB: '+user);
    	}
    	
      //tear-down and build sidebar
      if (document.querySelector("#entries-list")) {
        document.querySelector("#entries-list").innerHTML = '';
      }
	    document.getElementById('entries-list').appendChild(makeUL(data));
	    var j = 0;
	     data.entry.forEach(function (entry) {
	       document.getElementById('entry-li-' + j).onclick = function () {
			      populateEntryForm(entry);
		      };
		      j++;
	      });
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
  var submitButton = document.querySelector("#submit-button");
  submitButton.className = "pure-button";//this removes the disabled
}

function makeUL(data) {
  //TODO: erase list of past users
  	//if (document.querySelector("#entries-list").innerHTML !== null) {
  	  //document.querySelector("#entries-list").innerHTML = '';
  	//}
  	    
    // create list element
    var list = document.createElement('ul');
	  list.setAttribute("id", "entries-list");
    list.setAttribute("class", "pure-menu-list");
    
    for(var i = 0; i < data.entry.length; i++) {
        var question = data.entry[i].question;
  			console.log('Question: '+question);
        // create li element
        var item = document.createElement('li');
        item.setAttribute("class", "pure-menu-item");
        //TODO: put this in the JSON 
        //item.setAttribute("title", arr.nav[i].text);

    		var a = document.createElement('a');

    		a.setAttribute("id", "entry-li-"+i);
    		a.setAttribute("class", "pure-menu-link");
    		a.appendChild(document.createTextNode(data.entry[i].date));

        // set li contents:
        item.appendChild(a);

        // add li to list
        list.appendChild(item);
    }

    return list;
}

var email = document.querySelector("#user-email");
email.value = localStorage.getItem('user-email');
if (email.value !== '') {
  checkEmail(email);
}