<!DOCTYPE html>
<html lang="en-US">
<style>
html { 
  background: url(back.jpg) no-repeat center fixed; 
  background-size: cover;
}

/*body { 
  color: white; 
}*/
</style>
<head>
	<link rel="stylesheet" href="./main.css" type="text/css" />
	<title>Search</title>
	
</head>

<body>
	<div id="navigation">
		<a href= ./index.html >Home</a>
		<a href= ./S1.php >Search</a>
		<a href= ./I1.php >Add Actor/Director</a>
		<a href= ./I2.php >Add Movie Info</a>
		<a href= ./I3.php >Add Comments</a>
        <a href= ./I4.php >Add Movie/Actor Relation</a>     
        <a href= ./I5.php >Add Movie/Director Relation</a> 
		<a href= ./B1.php >Show Actors</a>
		<a href= ./B2.php >Show Movies</a>
	</div>

<div id ="content">
	<h1> Search Actor or Movie ! </h1>
		<p>Please enter keywords here!</p>
<style>
input[type=text], select {
    
    padding: 10px 12px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=submit] {
   
    background-color: #FFC0CB;
    color: white;
    padding: 10px 15px;
    margin: 10px 4;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #A9A9A9;
}
</style>
		<form action="#" method="GET">
			<textarea name="q" rows="1" cols="80"></textarea>
			<input type="submit" value="Search">
		</form>

<?php
if ( !empty( $_GET['q'] ) ) {
	
	$db = new mysqli('localhost', 'cs143', '', 'CS143');

	if($db->connect_errno > 0){
    	die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $_GET['q'] ;
	echo "<br>";
	echo "Searching for: <code>{$_GET['q']}</code><br>";
	echo "<hr>";
// actor search
	$rs=$db->query("SELECT id,dob,concat(first,' ',last) as fullname FROM Actor WHERE concat(first,' ',last) LIKE '%$query%' order by id;");
	?>
<h3> Matching Actors: </h3>
<?php

	if($rs->num_rows ==0){
		print 'Empty set!';
		echo "<br>";
	}
	else{
	$answer = $rs->fetch_assoc();

	echo '<table id="customers">';
	echo "<tr><th>Actor</th><th>Date of birth</th></tr>";
	do{
		// echo "Actor: ";
		// print "<a href =./actorinfo.php?aid=".$answer[id].">";
		// echo $answer[first]." ".$answer[last];
		// print "</a><br>"; 
		echo "<tr>
    <td><a href=\"./actorinfo.php?aid={$answer[id]}\">{$answer[fullname]}</a></td>
    <td>{$answer[dob]}</td>
    </tr>";
	}while($answer = $rs->fetch_assoc());
	echo '</table>';
	}
// movie search

	$rs2=$db->query("SELECT * FROM Movie WHERE title LIKE '%$query%' order by id;");

	?>
<h3> Matching Movies: </h3>
<?php

	if($rs2->num_rows ==0){
		print 'Empty set!';
		echo "<br>";
	}
	else{
	$answer2 = $rs2->fetch_assoc();
	echo '<table id="customers">';
	echo "<tr><th>Movie</th><th>Year</th><th>Rating</th></tr>";
	do{
		// echo "Movie: ";
		// print "<a href =./movieinfo.php?mid=".$answer2[id].">";
		// echo $answer2[title];
		// print "</a><br>"; 
		echo "<tr>
    <td><a href=\"./movieinfo.php?mid={$answer2[id]}\">{$answer2[title]}</a></td>
    <td>{$answer2[year]}</td>
    <td>{$answer2[rating]}</td>
    </tr>";
	}while($answer2 = $rs2->fetch_assoc());
	echo '</table>';
	}
	$db->close();
}

?>
</div>
	</body>
</html>
