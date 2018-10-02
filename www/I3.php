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
	<title>Add Comments</title>
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

  <?php 
    $db = new mysqli('localhost', 'cs143', '', 'CS143');

    if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    
    if($_GET[mid]){
      $sqlMovie = "SELECT id,title FROM Movie WHERE id=$_GET[mid];";
    }else{   
      $sqlMovie = "SELECT id,title FROM Movie ORDER BY title ASC;";
    }
    $rs_Movie = $db->query($sqlMovie);
    if (!($rs_Movie)){
      $errmsg = $db->error;
      die("Query sqlMovie failed: $errmsg <br />");
    }
  ?>

  
<h1>Add comments</h1> 

<style>
input[type=text], select {
    
    padding: 8px 10px;
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
<FORM METHOD = "POST" ACTION = "./I3.php">
  
  Movie:
  <SELECT NAME="movie">
    <?php
      while($rsMovie = $rs_Movie->fetch_assoc()){
        $movieid = $rsMovie["id"];
        $title = $rsMovie["title"];
        echo "<option value = $movieid>$title</option>\n";
      }
    ?>
  </SELECT><br>

    <!-- Input your name, NAME is 'yn', default name is "Anonymous" -->
    Your Name:
    <INPUT TYPE = "text" NAME="yn" VALUE="Anonymous" SIZE=15 MAXLENGTH=20> <br>

    <!-- Select rating from Scroll Menu, NAME is 'rating' -->
    Rating:
    <SELECT NAME="rating">
    <OPTION>5 - Excellent!
    <OPTION>4 - Good!
    <OPTION SELECTED>3 - OK <!-- default is 3 -->
    <OPTION>2 - Not worth watching!
    <OPTION>1 - Don't watch it!
    </SELECT> <br>

    <!-- INPUT comments to TEXTAREA, NAME is 'comments'-->
    Comments:
    <br>
    <TEXTAREA NAME = "comments" ROWS="5" COLS="65" MAXLENGTH="500"></TEXTAREA> 
    <br>
      <INPUT TYPE="submit" VALUE="done">
</FORM>
      
<!-- INSERT data into database if there was input from user -->
<?php 
if(isset($_POST[movie]) && isset($_POST[yn]) && isset($_POST[rating]) && isset($_POST[comments])){
        $mid = $_POST[movie];
        $sqlMovieName = $db->query("SELECT title FROM Movie WHERE id = $mid");
	$MovieName =  $sqlMovieName->fetch_assoc();

	$rate = substr($_POST[rating],0,1); 
	$curtime = date('Y-m-d H:i:s'); //$curtime is current time
	echo "<b>(Attention: Each username has only one chance to comment on each movie!)</b>";
  echo "<br>";
  echo "<br>";
  if($_POST[comments]!=NULL){ 
    $tt= $db->query("INSERT INTO Review (name,time,mid,rating,comment) VALUES('$_POST[yn]','$curtime' ,$mid,$rate,'$_POST[comments]')");

    if($tt==NULL){
      echo "User has made comments already!";
    }else{
      echo "<b>Successfully added your comment!</b>";
    }

  }else{
    echo "<b>Comment is required!</b>";
    echo "<br>";
  }
}

$rs = $db->query("SELECT * from Review where mid=$mid");
$answer = $rs->fetch_assoc();
  
?>

<h2>Current comments for this movie: </h2> 
<?php 
  
  $avgrating=$db->query("SELECT avg(rating) FROM Review WHERE mid=$mid");
  $avg = $avgrating->fetch_row();

  print 'Movie Title: '.$MovieName['title'];
  print '<table id="customers"><tr>';
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
 print 'Total results: ' . $rs->num_rows;
   echo "<br>";
   echo "<br>";
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
  
$db->close();
?>
</div>
</body>
</html>