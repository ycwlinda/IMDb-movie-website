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
	<title>Add Movie/Actor Relation</title>
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

    $sqlTitle = "SELECT id, title, year FROM Movie ORDER BY title ASC;";
    $rs_title = $db->query($sqlTitle);
    if (!($rs_title)){
      $errmsg = $db->error;
      die("Query sqlTitle failed: $errmsg <br />");
    }

    $sqlActor = "SELECT id, last, first, dob FROM Actor ORDER BY first,last ASC;";
    $rs_actor = $db->query($sqlActor);
    if(!$rs_actor){
      $errmsg = $db->error;
      die("Query sqlActor failed: $errmsg <br />");
    }
  ?>

<h1>Add New Movie/Actor Relation</h1> 

<?php 
if (empty($_POST['title'])){
    $titleErr = "Please select a movie";
}else{
    $mid = $_POST['title'];
}

if (empty($_POST['actor'])){
    $actorErr = "Please select an actor";
}else{
    $aid = $_POST['actor'];
}

if (empty($_POST['role'])){
    $roleErr = "Actor must have a role";
}else{	
    $role = $_POST['role'];	
}

$valid = $mid && $aid && $role;
?>
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
<!-- FORM to input datas, POST -->
<FORM METHOD = "POST" ACTION = "./I4.php">
  <p><span class="error">* Required field.</span></p>

  Movie Title:
  <SELECT NAME="title">
    <option value = ''></option>
    <?php
      while($rsMovie = $rs_title->fetch_assoc()){
        $movieid = $rsMovie["id"];
        $title = $rsMovie["title"];
        $year = $rsMovie["year"];
        echo "<option value = $movieid>$title ($year)</option>\n";
      }
    ?>
  </SELECT>
  <span class="error">* <?php echo "$titleErr";?></span><br/><br/>

  Actor:
  <SELECT NAME="actor">
    <option value = ''></option>
    <?php
      while($rsActor = $rs_actor->fetch_assoc()){
        $actorid = $rsActor["id"];
        $last = $rsActor["last"];
        $first = $rsActor["first"];
        $dob = $rsActor["dob"];
        echo "<option value = $actorid>$first $last ($dob)</option>\n";
      }
    ?>
  </SELECT>
  <span class="error">* <?php echo "$actorErr";?></span><br/><br/>

  Role:
  <INPUT TYPE = "text" NAME="role" VALUE="" SIZE=40 MAXLENGTH=50> 
  <span class="error">* <?php echo "$roleErr";?></span><br/><br/>
  
  <!-- Submit -->
  <INPUT TYPE="submit" VALUE="Add">

</FORM>

<!-- INSERT data into database if there was input from user -->
<?php 
if($valid){

  $sqlInsert = "INSERT INTO MovieActor VALUES('$mid', '$aid', '$role');";
  if (!($rs = $db->query($sqlInsert))){
    $errmsg = $db->error;
    die("Query sqlInsert failed: $errmsg <br />");
  }
  echo "<br><b>Successfully added movie/actor relation!</b>";

  $rs = $db->query("SELECT * FROM MovieActor WHERE mid=$mid AND aid=$aid");
  $answer = $rs->fetch_assoc();
  echo "<h2>Current information for this Movie/Actor Relation: </h2>";
  echo "<b>New Movie/Actor Relation</b>";
}
?>  

<?php

  //print added movie
  print '<table id="customers"><tr>';
  foreach (array_keys($answer) as $col ) {
     print '<td>' . $col . '</td>';
  }
  print '</tr>';
  do{		
    print '<tr>';
    foreach ($answer as $col) {
      print '<td>' . $col . '</td>';
    }
    print '</tr>';	
  }while($answer = $rs->fetch_assoc());
  print '</table>';
  echo "<br>";
  if($rs->num_rows ==0){
    print 'Empty set!';
    echo "<br>";
  }
  $rs->free();

  $db->close();
?> 
</div>
</body>
</html>