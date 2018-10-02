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
	<title>Add Movie Info</title>
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

<h1>Add New Movie</h1> 

<?php 
if (empty($_POST['title'])){
    $titleErr = "Movie Title is required";
}else{
    $title = $_POST['title'];
}
if (empty($_POST['company'])){
    $companyErr = "Movie company is required";
}else{
    $company = $_POST['company'];
}
if (empty($_POST['year'])){
    $yearErr = "Movie year is required";
}else{	
    $year = $_POST['year'];	
    $pattern = "/^\d{4}$/";	//year
    if(!preg_match($pattern, $year)){
        $yearErr = "Invalid format for movie year";
        $year = "";
    }
}
$rating = $_POST['rating'];
$valid=$title && $company && $year;
?>
<style>
input[type=text], select {
    
    padding: 8px 10px;
    margin: 5px 0;
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
<FORM METHOD = "POST" ACTION = "./I2.php">
  <p><span class="error">*  Required field.</span></p>

  Title:
  <INPUT TYPE = "text" NAME="title" VALUE="" SIZE=20 MAXLENGTH=100> 
  <span class="error">* <?php echo "$titleErr";?></span><br/><br/>
  
  <!--  <br/><br/> -->

  Company:
  <INPUT TYPE = "text" NAME="company" VALUE="" SIZE=20 MAXLENGTH=50> 
  <span class="error">* <?php echo "$companyErr";?></span><br/><br/>

  Year:
  <INPUT TYPE = "text" NAME="year" VALUE="" SIZE=20 MAXLENGTH=11>
  <span class="error">* <?php echo "$yearErr";?></span><br/><br/>
  

  MPAA Rating:
  <SELECT NAME="rating">
    <option value="G">G</option>
    <option value="NC-17">NC-17</option>
    <option value="PG">PG</option>
    <option value="PG-13">PG-13</option>
    <option value="R">R</option>
    <option value="surrendere">surrendere</option>
  </SELECT> 
  <span class="error">* <?php echo "$rateErr";?></span><br/><br/>

  Genre:
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Action"> Action </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Adult"> Adult </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Adventure"> Adventure </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Animation"> Animation </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Comedy"> Comedy </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Crime"> Crime </INPUT><br>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Documentary"> Documentary </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Drama"> Drama </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Family"> Family </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Fantasy"> Fantasy </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Horror"> Horror </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Musical"> Musical </INPUT><br>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Mystery"> Mystery </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Romance"> Romance </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Sci-Fi"> Sci-Fi </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Short"> Short </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Thriller "> Thriller </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="War"> War </INPUT>
  <INPUT TYPE = "checkbox" NAME="genre[]" VALUE="Western"> Western </INPUT>
  <br>
  <br>
  <!-- Submit -->
  <INPUT TYPE="submit" VALUE="Add">

</FORM>

<!-- INSERT data into database if there was input from user -->
<?php 
if($valid){
  if(isset($_POST['genre'])){
    $NewGenres = array();
    foreach ($_POST['genre'] as $gvalue) {
      array_push($NewGenres,$gvalue);
    }
  }

  $sqlCheckMovieDupl = "SELECT id FROM Movie WHERE title='$title' AND company='$company' AND year='$year' AND rating='$rating';";
  if($rs = $db->query($sqlCheckMovieDupl)){
    $ExistMovieID = $rs->fetch_assoc();

    $sqlExistMovieGenre = "SELECT * FROM MovieGenre WHERE mid=$ExistMovieID[id];";
    if($rs = $db->query($sqlExistMovieGenre)){
      $ExistGenres = array();
      while($ExistMovieGenre = $rs->fetch_assoc()){
        array_push($ExistGenres,$ExistMovieGenre['genre']);
      }

      if($NewGenres==$ExistGenres){
        die("<br><b>Movie Existed!</b>");
      }
    }
  }

  //Update MaxMovieID
  $sqlMaxID = "SELECT id FROM MaxMovieID;";
  if (!($rs = $db->query($sqlMaxID))){ 
      $errmsg = $db->error;
      die("Query sqlMaxID failed: $errmsg <br />");
  }
  $MaxMovieID = $rs->fetch_assoc();
  $newID = $MaxMovieID[id]+1; 
  $sqlUpdateID = "UPDATE MaxMovieID SET id= $newID;";
  if (!($rs = $db->query($sqlUpdateID))){ 
      $errmsg = $db->error;
      die("Query sqlUpdate failed: $errmsg <br />");
  }

  //Insert Movie
  $sqlInsert = "INSERT INTO Movie VALUES('$newID', '$title', '$year', '$rating','$company');";
  if (!($rs = $db->query($sqlInsert))){
      $newID = $newID-1;
      $db->query("UPDATE MaxMovieID SET id= $newID");
      $errmsg = $db->error;
      die("Query sqlInsertMovie failed: $errmsg <br />");
  }

  //Insert MovieGenre
  if(isset($_POST['genre'])){
    foreach ($_POST['genre'] as $gvalue) {
      //echo $gvalue;
      $sqlInsert = "INSERT INTO MovieGenre VALUES('$newID', '$gvalue');";
      if (!($rs = $db->query($sqlInsert))){ 
          $errmsg = $db->error;
	  die("Query sqlInsertMovieGenre failed: $errmsg <br />");
      }
    }
  }
  echo "<br><b>Successfully added your movie information!</b>";

  echo "<h2>Current information for this movie</h2>";

  $rs1 = $db->query("SELECT id FROM MaxMovieID");
  $answer1 = $rs1->fetch_assoc();
  print '<br><b>MaxMovieID:</b>' .$answer1[id];
  echo"<br>";
  $rs1->free();

  $rs2 = $db->query("SELECT * FROM Movie WHERE id=$newID");
  $rs3 = $db->query("SELECT * FROM MovieGenre WHERE mid=$newID");
}
?>  

<?php

  //print added movie
  $answer2 = $rs2->fetch_assoc();
  echo "<br><b>New Movie Information</b>";
  print '<table id="customers"><tr>';
  foreach (array_keys($answer2) as $col ) {
     print '<td>' . $col . '</td>';
  }
  print '</tr>';
  do{		
    print '<tr>';
    foreach ($answer2 as $col) {
      print '<td>' . $col . '</td>';
    }
    print '</tr>';	
  }while($answer2 = $rs2->fetch_assoc());
  print '</table>';
  echo "<br>";
  if($rs2->num_rows ==0){
    print 'Empty set!';
    echo "<br>";
  }
  $rs2->free();
  
  //print MovieGenre
  $answer3 = $rs3->fetch_assoc();
  echo "<br><b>New Movie Genre Information</b>";
  print '<table id="customers"><tr>';
  foreach (array_keys($answer3) as $col ) {
     print '<td>' . $col . '</td>';
  }
  print '</tr>';
  do{		
    print '<tr>';
    foreach ($answer3 as $col) {
      print '<td>' . $col . '</td>';
    }
    print '</tr>';	
  }while($answer3 = $rs3->fetch_assoc());
  print '</table>';
  echo "<br>";
  if($rs3->num_rows ==0){
    print 'Empty set!';
    echo "<br>";
  }
  $rs3->free();

  $db->close();
?> 
</div>
</body>
</html>