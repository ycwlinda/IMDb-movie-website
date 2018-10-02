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
	<title>Browse Actor</title>
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
    $db = new mysqli('localhost', 'cs143','', 'CS143');

  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    $ActorInfoResult = $db->query("SELECT concat(concat(concat(concat(concat(concat(first,' '),last),' '),'('),dob),')'),id FROM Actor ORDER BY first,last ASC");	
  ?>

<h1>Browse Actors </h1>
<style>
input[type=text], select {
    width: 100%;
    padding: 15px 20px;
    margin: 8px 4px;
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

div {
    border-radius: 5px;
    padding: 10px;
}
</style>
<FORM METHOD = "GET" ACTION = "./actorinfo.php">
Choose Actor:
<SELECT NAME="aid">
   <?php 
      while($ActorInfo = $ActorInfoResult->fetch_row()){
?>
<OPTION value="<?php echo $ActorInfo[1];?>" >  
<?php echo $ActorInfo[0]; ?> 
</OPTION>
      <?php } 

    $db->close();
?>
</SELECT> <br>
<INPUT TYPE="submit" VALUE="Search">
</FORM>

</div>

</body>
</html>