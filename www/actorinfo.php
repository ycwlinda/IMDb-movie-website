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
	<title>Actor information</title>
	
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

	<h1> Show actor information </h1>

  <?php 
    $db = new mysqli('localhost', 'cs143','', 'CS143');

	if($db->connect_errno > 0){
    	die('Unable to connect to database [' . $db->connect_error . ']');
    }
    
    $rs=$db->query("SELECT * FROM Actor WHERE id=$_GET[aid]");
    $actorinfo=$rs->fetch_assoc();
    ?>


<p> Name: <?php echo $actorinfo[first].' '.$actorinfo[last]; ?> </p>
<p> Sex: <?php echo $actorinfo[sex]; ?> </p>
<p> Date Of Birth: <?php echo $actorinfo[dob]; ?> </p>
<p> Date Of Death: 
<?php 
	if($actorinfo[dod]==NULL)
		{echo "Still Alive";} 
	else 
		{echo $actorinfo[dod];} ?> </p>


<h2> -------------- Act in -------------- </h2>
<?php 
	$rs2 = $db->query("SELECT * FROM MovieActor WHERE aid=$_GET[aid]");
	if($rs2->num_rows ==0){
		print 'Empty set!';
		echo "<br>";
	}
	else{
	$row = $rs2->fetch_assoc();

	echo '<table id="customers">';
	echo "<tr><th>Actor Role</th><th>In Movie</th></tr>";
	do{
	// echo "Acted ";
	// echo $row[role];
	// echo " in ";
	$MovieNameResult= $db->query("SELECT title FROM Movie WHERE id=$row[mid]");
	$MovieName = $MovieNameResult->fetch_assoc();

	echo "<tr>
	<td>{$row[role]}</td>
    <td><a href=\"./movieinfo.php?mid={$row[mid]}\">{$MovieName[title]}</a></td>
    </tr>";
	
	// print "<a href =./movieinfo.php?mid=".$row[mid].">";
	// echo $MovieName[title]; 
	// print "</a><br>"; 

    }while($row = $rs2->fetch_assoc());
	echo '</table>';

	}

    $db->close();
?>
</div>
</body>
</html>