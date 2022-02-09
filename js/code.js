const urlBase = 'http://lotsa.yachts/api';
const extension = 'php';

var userId = 0;
var currentContactID = 0;
let firstName = "";
let lastName = "";

var pageNum = 1;
var pageMax = 1;

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";

	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;
	let hash = md5( password );

	document.getElementById("loginResult").innerHTML = "";
	let tmp = {Username:login,Password:hash};

  let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/Login.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( userId < 1 )
				{
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect ";
					return;
				}

				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

				saveCookie();

				window.location.href = "contacts.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

function comparePasswords()
{
	let password = document.getElementById("registerPassword");
	let passwordConfirm = document.getElementById("registerPasswordConfirm");

	if(password.value == passwordConfirm.value)
		passwordConfirm.setCustomValidity('');
	else
		passwordConfirm.setCustomValidity('Passwords must match');
}

function registerUser()
{
  if(!document.getElementById("registerForm").reportValidity())
    return;

	let firstNameInput = document.getElementById("firstName").value;
	let lastNameInput = document.getElementById("lastName").value;
	let email1 = document.getElementById("email1").value;
	let email2 = document.getElementById("email2").value;
	let email = email1 + "@" + email2;
	let login = document.getElementById("registerName").value;
	let password = document.getElementById("registerPassword").value;
	var hash = md5( password );

	let tmp = {FirstName:firstNameInput,LastName:lastNameInput,Email:email,Username:login,Password:hash};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/CreateUser.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				error = jsonObject.error;
		    	if (error == "")
  				{
  					window.location.href = "index.html";
  					return;
  				}
        		document.getElementById("registerResult").innerHTML = error;
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("registerResult").innerHTML = err.message;
	}

}


function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ",currentContactId=" + currentContactID + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++)
	{
		let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
    	else if( tokens[0] == "currentContactId" )
		{
			currentContactID = tokens[1];
		}
	}

	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
}

function doLogout()
{
	userId = 0;
  currentContactID = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function addContact()
{
  readCookie();
  if(!document.getElementById("contactForm").reportValidity())
    return;

	let firstNameInput = document.getElementById("firstName").value;
	let lastNameInput = document.getElementById("lastName").value;
  let streetAddress = document.getElementById("streetAddress").value;
  let city = document.getElementById("city").value;
  let state = document.getElementById("state").value;
  let country = document.getElementById("country").value;
  let zipCode = document.getElementById("zipCode").value;
	let email1 = document.getElementById("email1").value;
	let email2 = document.getElementById("email2").value;
  let email = email1 + "@" + email2;
  if(email == "@")
    email = "";
	let phone = document.getElementById("phoneNumber").value;

	document.getElementById("contactAddResult").innerHTML = "";

	let tmp = {UserID:userId,FirstName:firstNameInput,LastName:lastNameInput,AddressOne:streetAddress,City:city,State:state,Country:country,ZipCode:zipCode,Email:email,PhoneNumber:phone};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
          window.location.href = "contacts.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}

}
function doEditContact(contactID)
{
  currentContactID = contactID;
  saveCookie();
  window.location.href = "editcontact.html";
}
function loadEditContact()
{
  readCookie();
  let tmp = {ID:currentContactID};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/SearchbyID.' + extension;
  let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
        let contact = JSON.parse(xhr.responseText);

        document.getElementById("firstName").setAttribute("value", contact.firstName);
        document.getElementById("lastName").setAttribute("value", contact.lastName);
        document.getElementById("streetAddress").setAttribute("value", contact.address1);
        document.getElementById("city").setAttribute("value", contact.city);
        document.getElementById("state").setAttribute("value", contact.state);
        document.getElementById("country").setAttribute("value", contact.country);
        document.getElementById("zipCode").setAttribute("value", contact.zipCode);
        document.getElementById("phoneNumber").setAttribute("value", contact.phoneNumber);
        let email = contact.email;
        if(email.includes("@"))
        {
          let emailSplit = email.split("@");
          document.getElementById("email1").value = emailSplit[0];
          document.getElementById("email2").value = emailSplit[1];
          document.getElementById("email2").value = emailSplit[1];
        }
        let button = document.getElementById("confirmButton");
        button.setAttribute("onclick", "editContactConfirm(" + contact.ID + ");");
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}
function editContactConfirm(contactID)
{
  let ID = contactID;
	let firstNameInput = document.getElementById("firstName").value;
	let lastNameInput = document.getElementById("lastName").value;
  let streetAddress = document.getElementById("streetAddress").value;
  let city = document.getElementById("city").value;
  let state = document.getElementById("state").value;
  let country = document.getElementById("country").value;
  let zipCode = document.getElementById("zipCode").value;
	let email1 = document.getElementById("email1").value;
	let email2 = document.getElementById("email2").value;
  let email = email1 + "@" + email2;
  if(email == "@")
    email = "";
	let phone = document.getElementById("phoneNumber").value;

  document.getElementById("contactEditResult").innerHTML = "";

	let tmp = {UserID:userId,FirstName:firstNameInput,LastName:lastNameInput,AddressOne:streetAddress,City:city,State:state,Country:country,ZipCode:zipCode,Email:email,PhoneNumber:phone,ID:ID};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/EditContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
           window.location.href = "contacts.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactEditResult").innerHTML = err.message;
	}
}

function deleteContact(contactID)
{
	let tmp = {ID:contactID};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/DeleteContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
          		console.log(xhr.response);
          		searchContact();
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}
function searchContact()
{
	readCookie();
	let srch = document.getElementById("searchBar").value;
	document.getElementById("contactSearchResult").innerHTML = "";

	let tmp = {UserID:userId,fullName:srch};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/SearchContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("contactSearchResult").innerHTML = "";
				let contacts = JSON.parse(xhr.responseText);
				//console.log(contacts);
				pageMax = Math.ceil(contacts.length / 5);
				createTable(contacts, contacts.length);
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}

function createTable(contacts, numContacts)
{
  var tblBody = document.getElementById("tableBody");
  tblBody.innerHTML = "";
  var row;
  var cell;
  var cellText;

  //determines contacts on page
  var firstIndex = (pageNum - 1) * 5;
  var finalIndex =  pageNum * 5 - 1;
  if (finalIndex >= numContacts)
    finalIndex = numContacts - 1;

  //creates a row for every contact
  for(var i = firstIndex; i <= finalIndex; i++)
  {
    row = document.createElement("tr");
    //Name
    cell = document.createElement("td");
	cell.className = "text-center mt-5";
    cellText = document.createTextNode(contacts[i].firstName + " " + contacts[i].lastName);
    cell.appendChild(cellText);
    row.appendChild(cell);

    //Phone Number
    cell = document.createElement("td");
	cell.className = "text-center mt-5";
    cellText = document.createTextNode(contacts[i].phoneNumber);
    cell.appendChild(cellText);
    row.appendChild(cell);

    //Email Address
    cell = document.createElement("td");
	cell.className = "text-center mt-5";
    cellText = document.createTextNode(contacts[i].email);
    cell.appendChild(cellText);
    row.appendChild(cell);

    //Address
    cell = document.createElement("td");
	cell.className = "text-center mt-5";
    cellText = document.createTextNode(
      contacts[i].address1 + "\n" +
      contacts[i].city + " " + contacts[i].state + " " + contacts[i].zipCode + "\n" +
      contacts[i].country);
    cell.appendChild(cellText);
    row.appendChild(cell);

    cell = document.createElement("td");

    //Edit Button
    button = document.createElement("button");
    button.className = "btn btn-outline-primary";
    button.style.visibility = "hidden";
    button.setAttribute("onclick", "doEditContact(" + contacts[i].ID + ");");
    button.innerHTML = "Edit";
    cell.appendChild(button);

    //Delete Button
    button = document.createElement("button");
    button.className = "btn btn-outline-danger";
    button.style.visibility = "hidden";
    //button.setAttribute("onclick", "deleteContact(" + contacts[i].ID + ");");
    button.setAttribute("onclick", "if (confirm('Delete this contact?') == true) deleteContact(" + contacts[i].ID + ");");
    
    button.innerHTML = "Delete";
    button.style.marginLeft = "5px";
    cell.appendChild(button);

    row.appendChild(cell);
    row.setAttribute("id", "row" + i);
    
    row.addEventListener("mouseenter", function(e) {
      e.target.lastChild.firstChild.style.visibility = "visible";
      e.target.lastChild.lastChild.style.visibility = "visible";
    });
    
    row.addEventListener("mouseleave", function(e) {
      e.target.lastChild.firstChild.style.visibility = "hidden";
      e.target.lastChild.lastChild.style.visibility = "hidden";
    });
    
    tblBody.appendChild(row);
  }
}

function nextPage()
{
  if(pageNum < pageMax)
  {
    pageNum++;
    document.getElementById("pageItem").innerHTML = pageNum;
    searchContact();
  }
}
function prevPage()
{
  if(pageNum > 1)
  {
    pageNum--;
    document.getElementById("pageItem").innerHTML = pageNum;
    searchContact();
  }
}
function gotoPage(pageValue)
{
  pageNum = pageValue;
  document.getElementById("pageItem").innerHTML = pageNum;
  searchContact();
}

