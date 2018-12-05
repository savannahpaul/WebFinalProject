window.onload = function(){
	getposts();
	setInterval(checkNew, 5000);
}

function getposts(){
	dbreq = new XMLHttpRequest();
	dbreq.onreadystatechange=function(){
		document.getElementById("postlocation").innerHTML=this.responseText;
		document.getElementById("postcheck").innerHTML="";
	}
	dbreq.open("GET", "getposts.php", true);
	dbreq.send();
}

function checkNew(){
	dbreq = new XMLHttpRequest();
	dbreq.onreadystatechange=function(){
		if(this.responseText != 0){
			document.getElementById("postcheck").innerHTML=this.responseText+" new posts. <button class='like' onClick='getposts()'>Show?</button>";
		}
	}
	dbreq.open("GET", "newposts.php", true);
	dbreq.send();
}

function clic(element){
	//modify this to update to database
	var x = document.getElementById("postcontent");
	var y = document.getElementById("postlocation");
	var append = "<div class='post'><div class='postheader'><span class='poster'>Username</span> - 11/28/18 - Post #1 - Likes: 0</div><div class='postcontent'>" + x.value +"</div><div class='postlikes'><button onClick='updateLike();' class='likebutton' value='1'>&#128077; Like</button></div></div>";
	y.innerHTML += append;
	//modify this to update to database
}

function updateLike(){
	dbreq = new XMLHttpRequest();
	dbreq.open("GET", "likes.php", true);
	dbreq.send();
}
