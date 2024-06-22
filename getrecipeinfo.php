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
  $query .= " ORDER BY ".$_POST['SortBy']." ".$_POST['Dir'];
}
else
{
  $_POST['SortBy'] = 'Name';
  $_POST['Dir'] = 'ASC';
}

$response = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($response)
{
  echo '<table cellspacing="5" cellpadding="8"><tr class="header">';
  
  $column_names = array(
    "Name" => "Recipe Name",
    "Course" => "Dish Type",
    "Instrument" => "Cooking Instrument",
    "Prep_time" => "Prep Time",
    "Cook_time" => "Cook Time",
    "Score" => "Review Score"
  );
  
  if($_POST['Dir'] == 'ASC')
  {
    $sorting_arrow = '▲';
    $opposite_dir = 'DESC';
  }
  else if($_POST['Dir'] == 'DESC')
  {
    $sorting_arrow = '▼';
    $opposite_dir = 'ASC';
  }
  
  echo '<form style="display:inline" action="getrecipeinfo.php" method="post">';
  echo '<input type="hidden" name="Dir" value="'.$opposite_dir.'">';
  echo '<input type="hidden" name="SearchBy" value="'.$_POST['SearchBy'].'">';
  echo '<input type="hidden" name="Term" value="'.$_POST['Term'].'">';
  
  //Print column headers
  foreach($column_names as $key => $long_name)
  {
    echo '<th><button class="inlinebutton" type="submit" name="SortBy" value="'.$key.'">';
    if($_POST['SortBy'] == $key)
      echo '<span style="text-decoration:underline">'.$long_name.'</span>'.$sorting_arrow;
    else
      echo $long_name;
    echo '</button></th>';
  }
  
  echo '</form>';

  // mysqli_fetch_array will return a row of data from the query
  // until no further data is available
  $isEmpty = true;
  while($row = mysqli_fetch_array($response))
  {
    if($row['Score'] == NULL)
      $realScore = "N.A.";
    else
      $realScore = $row['Score'];
    
    //<td style='font-size:30'>☑☐</td>
    echo "<tr><td><form action='viewrecipe.php' method='post'>
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
      <select class="menusearch right" name="SearchBy">
        <option value="Name" <?php if($_POST['SearchBy'] == 'Name')echo"selected='selected'";?>>Recipe name</option>
        <option value="Course" <?php if($_POST['SearchBy'] == 'Course')echo"selected='selected'";?>>Dish type</option>
        <option value="Instrument" <?php if($_POST['SearchBy'] == 'Instrument')echo"selected='selected'";?>>Cooking instrument</option>
        <option value="Time" <?php if($_POST['SearchBy'] == 'Time')echo"selected='selected'";?>>Total time within...</option>
        <option value="Score" <?php if($_POST['SearchBy'] == 'Score')echo"selected='selected'";?>>Score at least...</option>
        <option value="Ingredient" <?php if($_POST['SearchBy'] == 'Ingredient')echo"selected='selected'";?>>Includes ingredient</option>
      </select>
      <input class="menusearch right" name="Term" placeholder = "Search Term" value="<?php if(isset($_POST['Term']))echo $_POST['Term'];else echo 'Search Term';?>">
      <?php if(isset($_POST['SortBy']))echo "<input type='hidden' name='SortBy' value='".$_POST['SortBy']."'>";?>
      <?php if(isset($_POST['Dir']))echo "<input type='hidden' name='Dir' value='".$_POST['Dir']."'>";?>
      <button class="menubutton right" type="submit" name="submit" value="Submit"><b>Submit Search</b></button>
    </form>
  </div>
</div></div></div>

</body>