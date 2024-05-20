<head>
<title>View Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="container"><div class="box"><div class="box-row"> 
  <div class="box-cell edges">
    <h2>Recipe Database</h2>
    <button class="menubutton" onclick="window.location.href='addrecipe.php';"><b><span class="icon">➕</span>Add New Recipe</b></button>
    <button class="menubutton" onclick="window.location.href='getrecipeinfo.php';"><b><span class="icon">🔍</span>Search</b></button>
    <button class="menubutton" onclick="window.location.href='selection.php';"><b><span class="icon">☰</span>Manage Selection</b></button>
  </div>
  <div class="box-cell center">

<?php
// Get a connection for the database
require_once('mysqli_connect.php');

if(isset($_POST['Review']))
{
  $query = "INSERT INTO Review (Rname, Reviewer, Taste, Cost) VALUES (?, ?, ?, ?)";
    
  $stmt = mysqli_prepare($dbc, $query);
  mysqli_stmt_bind_param($stmt, "ssii", $_POST['Name'], $_POST['Reviewer'], $_POST['Taste'], $_POST['Cost']);
  mysqli_stmt_execute($stmt);
  
  $affected_rows = mysqli_stmt_affected_rows($stmt);
  if($affected_rows == 1)
  {
    mysqli_stmt_close($stmt);
  }
  else
  {
    echo 'Error occured!';
    echo mysqli_error($dbc);
  }
}

$query = "SELECT Rname, Iname, Amount, Unit
          FROM Ingredient
          WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";

$ingredients = @mysqli_query($dbc, $query);

if($ingredients)
{
  while($row = mysqli_fetch_array($ingredients))
  {
    $ingredient_list[] = $row['Iname'];
    $amount_list[] = $row['Amount'];
    $unit_list[] = $row['Unit'];
  }
}

if(isset($_POST['Select']))
{
  for($i = 0; $i < sizeof($ingredient_list); $i++)
  {
    $query = "INSERT INTO Selection (Rname, Iname, Amount, Unit) VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "ssds", $_POST['Name'], $ingredient_list[$i], $amount_list[$i], $unit_list[$i]);
    mysqli_stmt_execute($stmt);
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    if($affected_rows == 1)
    {
      mysqli_stmt_close($stmt);
    }
    else
    {
      echo 'Error occured!';
      echo mysqli_error($dbc);
    }
  }
}
else if(isset($_POST['Deselect']))
{
  $query = "DELETE FROM Selection
            WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entry<br />";
    echo mysqli_error($dbc);
  }
}

$query = "SELECT Name, Course, Instrument, Prep_time, Cook_time, Score
          FROM Recipe
          WHERE Name = \"".$dbc->escape_string($_POST['Name'])."\";";
$info = @mysqli_query($dbc, $query);

$query = "SELECT Rname, Iname, Amount
          FROM Selection
          WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";
$selectionInfo = @mysqli_query($dbc, $query);

$scoreQuery = "SELECT AVG(Taste) AS 'taste', AVG(Cost) AS 'cost' FROM Review WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";
    $scoreData = @mysqli_query($dbc, $scoreQuery);
    $Score = mysqli_fetch_array($scoreData);

// If the query executed properly proceed
if($info)
{
  $info = mysqli_fetch_array($info);
  echo '<h1 class="header">'.$info['Name'].'</h1>';
  echo ''.$info['Course'].'<br>';
  echo ''.$info['Instrument'].'<br>';
  echo '<b>Prep Time: </b>'.$info['Prep_time'].' minutes<br>';
  echo '<b>Cook Time: </b>'.$info['Cook_time'].' minutes<br>';

  if($Score['taste'] != NULL)
    echo '<b>Taste Score: </b>'.round($Score['taste'], 1).'<br>';

  if($Score['cost'] != NULL)
    echo '<b>Cost Efficiency Score: </b>'.round($Score['cost'], 1).'<br>';
}
else
{
  echo "Couldn't issue database query<br />";
  echo mysqli_error($dbc);
}

// If the query executed properly proceed
if($ingredients)
{
  echo '<table align="left"
  cellspacing="5" cellpadding="8">

  <tr><td style="padding: 12 12 0 0"><b>Ingredient</b></td>
  <td style="padding: 12 2 0 0"><b>Amount</b></td></tr>';

  for($i = 0; $i < sizeof($ingredient_list); $i++)
  {
    echo '<tr><td style="padding: 2 12 0 0">'.$ingredient_list[$i].'</td>
    <td style="padding: 2 2 0 0; text-align: right">'.$amount_list[$i].'</td>
    <td style="padding: 2 12 0 0">'.$unit_list[$i].'</td>';
    echo '</tr>';
  }
  echo '</table>';
}
else
{
  echo "Couldn't issue database query<br />";
  echo mysqli_error($dbc);
}

$query = "SELECT Rname, Step_num, Step_instruction
          FROM Instruction
          WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";

$instructions = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($instructions)
{
  echo '<table align="left"
  cellspacing="5" cellpadding="8">

  <tr><td></td>
  <td><b>Instructions</b></td></tr>';

  // mysqli_fetch_array will return a row of data from the query
  // until no further data is available
  while($row = mysqli_fetch_array($instructions))
  {
    echo '<tr><td>'.$row['Step_num'].')</td><td>'. 
    $row['Step_instruction'] . '</td>';
    echo '</tr>';
  }
  echo '</table>';
}
else
{
  echo "Couldn't issue database query<br />";
  echo mysqli_error($dbc);
}

  
  echo "</div><div class='box-cell edges'>
    <h2>Osterman 2019</h2>";

  if(mysqli_num_rows($selectionInfo)==0)
  {
    echo "<form style='display:inline' action='viewrecipe.php' method='post'><input type='hidden' name='Name' value=\"".$info['Name']."\"><button class='menubutton' type='submit' name='Select' value=\"".$info['Name']."\"><b>Add to Selection</b></button></form>";
  }
  else
  {
    echo "<form style='display:inline' action='viewrecipe.php' method='post'><input type='hidden' name='Name' value=\"".$info['Name']."\"><button class='menubutton' type='submit' name='Deselect' value=\"".$info['Name']."\"><b>Remove from Selection</b></button></form>";
  }
  
  echo "
    <form style='display:inline' action='review.php' method='post'><button class='menubutton' type='submit' name='Name' value=\"".$info['Name']."\"><b>Write Review</b></button></form>
    <form style='display:inline' action='getrecipeinfo.php' method='post' onsubmit=\"return confirm('Are you sure? This will permanently delete this recipe!');\"><button class='menubutton' type='submit' name='Delete' value=\"".$info['Name']."\"><b>Delete Recipe</b></button></form>
  </div>";

// Close connection to the database
mysqli_close($dbc);
?>
</div></div></div>

</body>