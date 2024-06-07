<head>
<title>Recipe List</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="container"><div class="box"><div class="box-row">
  <iframe class="iframe box-cell edges" src="sidebar.php"></iframe>
  <div class="box-cell center">
    
<?php
// Get a connection for the database
require_once('mysqli_connect.php');

if(!empty($_POST['Delete']))
{
  $query = "DELETE FROM Recipe
            WHERE Name = \"".$dbc->escape_string($_POST['Delete'])."\"";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entry<br />";
    echo mysqli_error($dbc);
  }
  
  $query = "DELETE FROM Ingredient
            WHERE Rname = \"".$dbc->escape_string($_POST['Delete'])."\"";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entry<br />";
    echo mysqli_error($dbc);
  }
  
  $query = "DELETE FROM Instruction
            WHERE Rname = \"".$dbc->escape_string($_POST['Delete'])."\"";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entry<br />";
    echo mysqli_error($dbc);
  }
  
  $query = "DELETE FROM Selection
            WHERE Rname = \"".$dbc->escape_string($_POST['Delete'])."\"";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entry<br />";
    echo mysqli_error($dbc);
  }
  
  $query = "DELETE FROM Review
            WHERE Rname = \"".$dbc->escape_string($_POST['Delete'])."\"";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entry<br />";
    echo mysqli_error($dbc);
  }
}

$query = "SELECT * FROM Recipe";
$response = @mysqli_query($dbc, $query);

while($row = mysqli_fetch_array($response))
{
  //run query to get average score
  $scoreQuery = "SELECT (AVG(Taste)+AVG(Cost))/2 AS 'avg' FROM Review WHERE Rname = \"".$dbc->escape_string($row['Name'])."\"";
  $scoreData = @mysqli_query($dbc, $scoreQuery);
  $Score = mysqli_fetch_array($scoreData);
  
  $newScoreQuery = "UPDATE Recipe SET Score = ".$Score['avg']." WHERE Name = \"".$dbc->escape_string($row['Name'])."\"";
  @mysqli_query($dbc, $newScoreQuery);
}
  
// Create a query for the database
$query = "SELECT Name, Course, Instrument, Prep_time, Cook_time, Score FROM Recipe";

//qualifies the query with search terms
if(isset($_POST['SearchBy']))
{
  $_POST['Term'] = str_replace("\"", "''", $_POST['Term']);
  if($_POST['SearchBy'] == 'Name')
    $query .= " WHERE Name LIKE \"%".$dbc->escape_string($_POST['Term'])."%\"";
  else if($_POST['SearchBy'] == 'Course')
    $query .= " WHERE Course LIKE \"%".$dbc->escape_string($_POST['Term'])."%\"";
  else if($_POST['SearchBy'] == 'Instrument')
    $query .= " WHERE Instrument LIKE \"%".$dbc->escape_string($_POST['Term'])."%\"";
  else if($_POST['SearchBy'] == 'Time')
    $query .= " WHERE Prep_time + Cook_time <= ".$dbc->escape_string($_POST['Term']);
  else if($_POST['SearchBy'] == 'Score')
    $query .= " WHERE Score >= ".$dbc->escape_string($_POST['Term']);
  else if($_POST['SearchBy'] == 'Ingredient')
    $query .= " WHERE EXISTS (SELECT * FROM Ingredient WHERE Rname = Name AND Iname LIKE \"%".$dbc->escape_string($_POST['Term'])."%\")";
}
else
{
  $_POST['SearchBy'] = '';
  $_POST['Term'] = '';
}

//qualifies the query with sorting method
if(isset($_POST['SortBy']))
{
  if($_POST['SortBy'] == 'Name')
    $query .= " ORDER BY Name ".$_POST['Dir'];
  else if($_POST['SortBy'] == 'Course')
    $query .= " ORDER BY Course ".$_POST['Dir'];
  else if($_POST['SortBy'] == 'Instrument')
    $query .= " ORDER BY Instrument ".$_POST['Dir'];
  else if($_POST['SortBy'] == 'Prep_time')
    $query .= " ORDER BY Prep_time ".$_POST['Dir'];
  else if($_POST['SortBy'] == 'Cook_time')
    $query .= " ORDER BY Cook_time ".$_POST['Dir'];
  else if($_POST['SortBy'] == 'Score')
    $query .= " ORDER BY Score ".$_POST['Dir'];
}
else
{
  $_POST['SortBy'] = 'Name';
}

$response = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($response)
{
  if(!isset($_POST['Dir']))
    $_POST['Dir'] = 'ASC';
  if($_POST['Dir'] == 'ASC')
  {
    echo '<table cellspacing="5" cellpadding="8"><tr class="header"><th align="left"';
    if($_POST['SortBy'] == 'Name') echo ' style="text-decoration:underline"';
    echo '><b>Recipe Name<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Name"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="DESC">▲</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Course') echo ' style="text-decoration:underline"';
    echo '><b>Dish Type<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Course"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="DESC">▲</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Instrument') echo ' style="text-decoration:underline"';
    echo '><b>Cooking Instrument<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Instrument"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="DESC">▲</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Prep_time') echo ' style="text-decoration:underline"';
    echo '><b>Prep Time<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Prep_time"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="DESC">▲</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Cook_time') echo ' style="text-decoration:underline"';
    echo '><b>Cook Time<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Cook_time"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="DESC">▲</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Score') echo ' style="text-decoration:underline"';
    echo '><b>Review Score<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Score"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="DESC">▲</button></form></b></th>';
  }
  else
  {
    echo '<table align="left" cellspacing="5" cellpadding="8"><tr class="header"><th align="left"';
    if($_POST['SortBy'] == 'Name') echo ' style="text-decoration:underline"';
    echo '><b>Recipe Name<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Name"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="ASC">▼</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Course') echo ' style="text-decoration:underline"';
    echo '><b>Dish Type<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Course"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="ASC">▼</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Instrument') echo ' style="text-decoration:underline"';
    echo '><b>Cooking Instrument<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Instrument"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="ASC">▼</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Prep_time') echo ' style="text-decoration:underline"';
    echo '><b>Prep Time<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Prep_time"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="ASC">▼</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Cook_time') echo ' style="text-decoration:underline"';
    echo '><b>Cook Time<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Cook_time"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="ASC">▼</button></form></b></th>';
    echo '<th align="left"';
    if($_POST['SortBy'] == 'Score') echo ' style="text-decoration:underline"';
    echo '><b>Review Score<form style="display:inline" action="getrecipeinfo.php" method="post"><input type="hidden" name="SortBy" value="Score"><input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'"><input type="hidden" name="Term" value="'.$_POST['Term'].'"><button class="inlinebutton" type="submit" name="Dir" value="ASC">▼</button></form></b></th>';
  }

  // mysqli_fetch_array will return a row of data from the query
  // until no further data is available
  $isEmpty = true;
  while($row = mysqli_fetch_array($response))
  {
    if($row['Score'] == NULL)
      $realScore = "N.A.";
    else
      $realScore = $row['Score'];
    
    echo "<tr><td align='left'><form action='viewrecipe.php' method='post'>
    <input class='inlinebutton' style='text-decoration:underline;' type='submit' name='Name' value= \"".$row['Name']."\">
    </form></td><td>" . 
    $row['Course'] . '</td><td>' .
    $row['Instrument'] . '</td><td>' . 
    $row['Prep_time'] . '</td><td>' .
    $row['Cook_time'] . '</td><td>' . 
    $realScore . '</td>';
    echo '</tr>';
    $isEmpty = false;
  }
  echo '</table>';
  
  if($isEmpty)
    echo '<br><br><br>No results!';
}
else
{
  echo "Couldn't issue database query<br />";
  echo mysqli_error($dbc);
}

// Close connection to the database
mysqli_close($dbc);

?>

  </div>
  <div class="box-cell edges">
    <h2>Osterman 2019</h2>
    <form style="display:inline" action="getrecipeinfo.php" method="post">
      <select class="menusearch" name="SearchBy">
        <option value="Name" <?php if($_POST['SearchBy'] == 'Name')echo"selected='selected'>";else echo ">";?>Recipe name</option>
        <option value="Course" <?php if($_POST['SearchBy'] == 'Course')echo"selected='selected'>";else echo ">";?>Dish type</option>
        <option value="Instrument" <?php if($_POST['SearchBy'] == 'Instrument')echo"selected='selected'>";else echo ">";?>Cooking instrument</option>
        <option value="Time" <?php if($_POST['SearchBy'] == 'Time')echo"selected='selected'>";else echo ">";?>Total time less than</option>
        <option value="Score" <?php if($_POST['SearchBy'] == 'Score')echo"selected='selected'>";else echo ">";?>Score greater than</option>
        <option value="Ingredient" <?php if($_POST['SearchBy'] == 'Ingredient')echo"selected='selected'>";else echo ">";?>Includes ingredient</option>
      </select>
      <input class="menusearch" name="Term" placeholder = "Search Term" value="<?php if(isset($_POST['Term']))echo$_POST['Term'];else echo 'Search Term';?>">
      <button class="menubutton" type="submit" name="submit" value="Submit"><b>Submit Search</b></button>
    </form>
  </div>
</div></div></div>

</body>