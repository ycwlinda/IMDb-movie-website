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
	<title>Movie Information</title>
	
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
	<h1> Show Movie information </h1>

  <?php 
    $db = new mysqli('localhost', 'cs143','', 'CS143');

	if($db->connect_errno > 0){
    	die('Unable to connect to database [' . $db->connect_error . ']');
    }
    
    $Movieresult = $db->query("SELECT * FROM Movie WHERE id=$_GET[mid]");
    $movieinfo=$Movieresult->fetch_assoc(); 


  ?>


<p>Title: <?php echo $movieinfo[title]." (".$movieinfo[year].")"; ?> </p>
<p>Producer: <?php echo $movieinfo[company]; ?> </p>
<p>MPAA Rating: <?php echo $movieinfo[rating]; ?> </p>
<p>Director: <?php 

    $MovieDirectorResult = $db->query("SELECT * FROM MovieDirector WHERE mid=$_GET[mid]");
    $MovieDirector=$MovieDirectorResult->fetch_assoc();

	$DirectorNameResult = $db->query("SELECT * FROM Director WHERE id=$MovieDirector[did]");
    if($DirectorNameResult!=NULL)
    	{
    		$DirectorName=$DirectorNameResult->fetch_assoc(); 
			echo $DirectorName[first]." ".$DirectorName[last]; 
	}
	else{
		print 'NULL!';
		echo "<br>";
	}
?> </p>
<p>Genre: 
	<?php
    $MovieGenreResult = $db->query("SELECT genre FROM MovieGenre WHERE mid=$_GET[mid]");
    $MovieGenre =$MovieGenreResult->fetch_assoc(); 
	if($MovieGenreResult->num_rows!=0)
		{
		do{	 
 	 echo $MovieGenre[genre];
 	 echo " ";
	}while( $MovieGenre = $MovieGenreResult->fetch_assoc());
	}
	else{
	print 'NULL!';
		echo "<br>";
}
	?>
</p>

<h2> -------------- Actors in this movie -------------- </h2>
<?php	   
	$MovieActorResult = $db->query("SELECT * FROM MovieActor WHERE mid=$_GET[mid]");
	$row = $MovieActorResult->fetch_assoc();
if($MovieActorResult->num_rows ==0){
		print 'Empty set!';
		echo "<br>";
	}
else{
echo '<table id="customers">';
echo "<tr><th>Actor</th><th>Role</th></tr>";
do{
	$ActorNameResult =$db->query("SELECT concat(first,' ',last) as fullname FROM Actor WHERE id=$row[aid]");
	$ActorName = $ActorNameResult->fetch_assoc();
	// print "<a href=./actorinfo.php?aid=".$row[aid].">";
	// echo $ActorName[first]." ".$ActorName[last]; 
	// print "</a>"; 
	// echo " act as ";
	// echo $row[role];
	// echo "<br>";
	echo "<tr>
    <td><a href=\"./actorinfo.php?aid={$row[aid]}\">{$ActorName[fullname]}</a></td>
    <td>{$row[role]}</td>
    </tr>";
}while($row = $MovieActorResult->fetch_assoc());
echo '</table>';
}
?> 
<h2> ------------------------ Reviews ------------------------ </h2>
<?php
	$rs = $db->query("SELECT time,name,rating,comment from Review where mid=$_GET[mid]");
  $answer = $rs->fetch_assoc();
  $avgrating=$db->query("SELECT avg(rating) from Review where mid=$_GET[mid]");
  $avg = $avgrating->fetch_row();
  
  ?>
 <!--  <p>Current comments for this movie: </p>  -->
 
 <?php 
  
  print '<table id="customers" ><tr>'; 

    foreach ( array_keys( $answer ) as $col ) {
      print '<td>' . $col . '</td>';
    }
    print '</tr>';
  do{
      print '<tr>';
      foreach ( $answer as $col ) {
        print '<td>' . $col . '</td>';
      }
      print '</tr>';  
  }while ( $answer = $rs->fetch_assoc() );

    print '</table>';

  echo "<br>";
  if($rs->num_rows ==0){
    print 'Empty set!';
    echo "<br>";
  }
  if($avg[0]!=NULL)
  {print 'Average rating: ' . $avg[0];}
else
  {print 'Average rating: 0';}
  print ' out of ' . $rs->num_rows;
  print ' comments.';
  echo "<br>";
  echo "<br>";
  $rs->free();
  $avgrating->free();
?> 
<h2>-------------- Leave your comments now! -------------- </h2> 
<?php
		echo "Comment on: ";
                print "<a href=\"./I3.php?mid=$_GET[mid]\">{$movieinfo[title]}</a>";
		//print "<a href =./I3.php >";
		//echo $movieinfo[title];
		print "</a><br>"; 

?>

</div>
</body>
</html>