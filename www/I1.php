<!DOCTYPE html>
<html lang="en-US">
<style>
html { 
  background: url(back.jpg) no-repeat center fixed; 
  background-size: cover;
}

</style>
<head>
	<link rel="stylesheet" href="./main.css" type="text/css" />
	<title>Add Actor/Director</title>
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
    ?>

<h1>Add new actor/director:</h1> 
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


<FORM METHOD = "POST" ACTION = "./I1.php">
  
  Identity:
  <INPUT TYPE = "radio" NAME="identity" VALUE="Actor" CHECKED> Actor
  <INPUT TYPE = "radio" NAME="identity" VALUE="Director"> Director <br> <br> 
  Sex:
  <INPUT TYPE = "radio" NAME="sex" VALUE="1" CHECKED> Male
  <INPUT TYPE = "radio" NAME="sex" VALUE="2"> Female <br> <br> 
  
  First Name:
  <INPUT TYPE = "text" NAME="fn" VALUE="" SIZE=15 MAXLENGTH=30> <br>
  Last Name:
  <INPUT TYPE = "text" NAME="ln" VALUE="" SIZE=15 MAXLENGTH=30> <br>
  
  
  
  Date of Birth:
  <INPUT TYPE = "text" NAME="dob" VALUE="" SIZE=15 MAXLENGTH=30> <br>
  Date of Death:
  <INPUT TYPE = "text" NAME="dod" VALUE="" SIZE=15 MAXLENGTH=30> <br>
  
  <INPUT TYPE="submit" VALUE="SUBMIT">

</FORM>

<!-- INSERT data into database if there was input from user -->
<?php 
if(isset($_POST[identity]) && isset($_POST[fn]) && isset($_POST[ln]) && isset($_POST[dob])){
//Incrementing MaxPersonID
  $MaxPersonIDResult = $db->query('SELECT id FROM MaxPersonID'); 
  $MaxPersonID = $MaxPersonIDResult->fetch_assoc(); 
  $newMaxPersonID =  $MaxPersonID[id]+1; 
  $db->query("UPDATE MaxPersonID SET id=$newMaxPersonID"); 

//Sex change 
$sex = "Male";
if($_POST[sex]==2){
	$sex = "Female";
}

//if Actor was chosen
if($_POST[identity]=='Actor'){
  
  if($_POST[dod]=='')
  {$CheckActorDupl = "SELECT first FROM Actor WHERE dob='$_POST[dob]' AND last='$_POST[ln]' AND sex='$sex' and dod is null ";
  }
  else
  {$CheckActorDupl = "SELECT first FROM Actor WHERE dob='$_POST[dob]' AND last='$_POST[ln]' AND sex='$sex' and dod='$_POST[dod]' ";
  }

  $actordup = $db->query($CheckActorDupl);
  if($actordup->num_rows!=0){
    
    $ExistActor = $actordup->fetch_assoc();

    if($_POST[fn]==$ExistActor[first])
    {  die("<br><b>Actor Existed fn!</b>"); }
  }

  if($_POST[dod]==''){ //if Date of Death was not set, dod=NULL
    $db->query("INSERT INTO Actor (id,last,first,sex,dob,dod) VALUES($newMaxPersonID,'$_POST[ln]','$_POST[fn]','$sex','$_POST[dob]',NULL)"); 
  } 
  else { //if Date of Death was set
    $db->query("INSERT INTO Actor (id,last,first,sex,dob,dod) VALUES($newMaxPersonID,'$_POST[ln]','$_POST[fn]','$sex','$_POST[dob]','$_POST[dod]')"); //Inserting to Actor
  }
}
//if Director was chosen (rest is same things as inserting to Actor)
if($_POST[identity]=='Director'){
  echo "Attention : Director doesn't have a gender value.";
  echo "<br>";
  
  if($_POST[dod]=='')
  {$CheckDirectorDupl = "SELECT first FROM Director WHERE dob='$_POST[dob]' AND last='$_POST[ln]' and dod is null ";
  }
  else
  {$CheckDirectorDupl = "SELECT first FROM Director WHERE dob='$_POST[dob]' AND last='$_POST[ln]' and dod='$_POST[dod]' ";
  }

  $directordup = $db->query($CheckDirectorDupl);
  if($directordup->num_rows!=0){
    
    $ExistDirector = $directordup->fetch_assoc();

    if($_POST[fn]==$ExistDirector[first])
    {  die("<br><b>Director Existed fn!</b>"); }
  }
  if($_POST[dod]==''){
    $db->query("INSERT INTO Director (id,last,first,dob,dod) VALUES($newMaxPersonID,'$_POST[ln]','$_POST[fn]','$_POST[dob]',NULL)");
  } else {
    $db->query("INSERT INTO Director (id,last,first,dob,dod) VALUES($newMaxPersonID,'$_POST[ln]','$_POST[fn]','$_POST[dob]','$_POST[dod]')");
  }
}    
echo "<br>";
echo $_POST[identity]." added "; //show what was added             
}
if($_POST[identity]=='Actor')
  {$rs = $db->query("SELECT * from Actor where id=$newMaxPersonID"); }
if($_POST[identity]=='Director')
  {$rs = $db->query("SELECT * from Director where id=$newMaxPersonID"); }
  $answer = $rs->fetch_assoc();
  
  if($rs->num_rows ==0)
    {echo "not successfully! ";
    $newMaxPersonID =$newMaxPersonID -1;
    $db->query("UPDATE MaxPersonID SET id=$newMaxPersonID");}

    echo "<br>";
  ?>


  <h2>Current information for this person: </h2> 
 <?php 
  print 'MaxPersonID:' .$newMaxPersonID;
  echo"<br>";
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
  // print 'Total results: ' . $rs->num_rows;
  $rs->free();


$db->close();
?>
</div>
</body>
</html>