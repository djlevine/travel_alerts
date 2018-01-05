function getData(value) {
	document.getElementById("overlay").style.display = "block";	
	document.getElementById("input").style.display = "none";	
	document.getElementById("toggle").style.display = "block";	
	if(!value)value="";
	var xhttp = new XMLHttpRequest();
	//Remove all children
	var oldContent = document.getElementById("results");
	while (oldContent.firstChild) {
	    oldContent.removeChild(oldContent.firstChild);
	}
	//Prepare the Ajax request
	xhttp.onreadystatechange = function() {
	  	if (this.readyState == 4 && this.status == 200) {
	   		//var myObj = 
	   		try{
	   			eval(myObj = JSON.parse(this.responseText));
	   		} catch(e){
	   			myObj = '';
	   			var response = {
	   				title:'An internal error occurred.',
	   				desc:'An internal error has occurred, please refresh the page and try again. If you continue to experience an issue, click the link below.',
	   				link:'<a href="mailto:dlevine@dlevine.us?subject=Travel Alerts Error!&body=I received an error in Travel Alerts. error: j404" id="infoLink" target="_blank">More Information</a>',
	   				pubDate:'',
	   				agency:'j404',
	   				abrv:'error'
	   			};
	   			writeToPage(response);
	   		}
	   		for (var i = 0, l = Object.keys(myObj).length; i < l; i++) {
	   			if(myObj[i][3] == "" || myObj[i][3] == null){}
	   			else{myObj[i][3] = "<a href='" + myObj[i][3] + "' id='infoLink' target='_blank'>More Information</a>";}

	   			var response = {
	   				title:myObj[i][1],
	   				desc:myObj[i][2],
	   				link:myObj[i][3],
	   				pubDate:myObj[i][4],
	   				agency:myObj[i][5],
	   				abrv:myObj[i][5]
	   			};
	   			writeToPage(response);
	   		}
	  	} else {

	  	}
	};
	xhttp.open("GET", "includes/accessData.php?q=" + value, true);
	xhttp.send();
}

function writeToPage(response){
	var newContent = document.createElement('div');
	newContent.className = 'updateItem' + ' ' + response.abrv; //Leave the space after the classname or they all mush together
	newContent.innerHTML = "<div class='title'>" + response.title + "<span class='agency'>" + response.agency + "</span></div><div class='status'>" + response.desc +"<br>" + 
	response.link + "</div><div class='pubDate'>" + response.pubDate + "</div>";
	document.getElementById('results').appendChild(newContent);
	document.getElementById("overlay").style.display = "none";
}
//Toggle the back button off and display the menu
function toggle(){
	document.getElementById("input").style.display = "block";	
	document.getElementById("toggle").style.display = "none";

	var oldContent = document.getElementById("results");
	while (oldContent.firstChild) {
	    oldContent.removeChild(oldContent.firstChild);
	}
}