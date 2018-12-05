setInterval(getposts, 1000);

function getposts(){
	dbreq = new XMLHttpRequest();
	dbreq.onreadystatechange=function(){
		document.getElementById("postlocation").innerHTML=this.responseText;
	}
	dbreq.open("GET", "getposts.php", true);
	dbreq.send();
	console.log("got posts");
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
