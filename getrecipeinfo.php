<head>
<title>Recipe List</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="selectaction.js"></script>
</head>

<body>

<iframe class="menubox main" src="sidebar.php"></iframe>

<?php
include("global.php");
global $allRname;

// Get a connection for the database
require_once('mysqli_connect.php');

if(!empty($_POST['Delete']))
{
  $escapedString = $dbc->escape_string($_POST['Delete']);
  $query = "DELETE FROM Recipe
            WHERE Name = '".$escapedString."';
            DELETE FROM Ingredient
            WHERE Rname = '".$escapedString."';
            DELETE FROM Instruction
            WHERE Rname = '".$escapedString."';
            DELETE FROM Selection
            WHERE Rname = '".$escapedString."';
            DELETE FROM Review
            WHERE Rname = '".$escapedString."'
            ";
  if(!@mysqli_multi_query($dbc, $query))
  {
    echo "<script>alert('Couldn't delete database entries\n".mysqli_error($dbc)."');</script>";
  }
  clearStoredResults();
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
?>

<div class="sidebar menubox context">
  <form action="getrecipeinfo.php" method="post">
    <select class="menusearch" name="SearchBy">
      <option value="Name" <?php if($_POST['SearchBy'] == 'Name')echo"selected='selected'";?>>Recipe name</option>
      <option value="Course" <?php if($_POST['SearchBy'] == 'Course')echo"selected='selected'";?>>Dish type</option>
      <option value="Instrument" <?php if($_POST['SearchBy'] == 'Instrument')echo"selected='selected'";?>>Cooking instrument</option>
      <option value="Time" <?php if($_POST['SearchBy'] == 'Time')echo"selected='selected'";?>>Total time within...</option>
      <option value="Score" <?php if($_POST['SearchBy'] == 'Score')echo"selected='selected'";?>>Score at least...</option>
      <option value="Ingredient" <?php if($_POST['SearchBy'] == 'Ingredient')echo"selected='selected'";?>>Includes ingredient</option>
    </select>
    <input class="menusearch" name="Term" placeholder="Search Term" value="<?php if(isset($_POST['Term']))echo $_POST['Term'];else echo 'Search Term';?>">
    <?php if(isset($_POST['SortBy']))echo "<input type='hidden' name='SortBy' value='".$_POST['SortBy']."'>";?>
    <?php if(isset($_POST['Dir']))echo "<input type='hidden' name='Dir' value='".$_POST['Dir']."'>";?>
    <button class="menubutton" type="submit" name="submit" value="Submit">Submit Search</button>
  </form>
</div>
<div class="center">

<table id=selectButtonContainer cellspacing="5" cellpadding="8">
  <tr class="header">
    <th>
      <button
        class="inlinebutton iconbutton"
        style="font-size:30; font-weight:normal"
        Rname="<?= $allRname ?>"
        nextSelectMethod=remove
        selectionCallbackFunction=reloadPage
        onclick="
          if(confirm('Are you sure? This will de-select all recipes!'))
            setSelectionStatus.call(this);
        ">
        ☒
      </button>
    </th>

<?php
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
?>

<form action="getrecipeinfo.php" method="post">
<input type="hidden" name="Dir" value="<?= $opposite_dir ?>">
<input type="hidden" name="SearchBy" value="<?= $_POST['SearchBy'] ?>">
<input type="hidden" name="Term" value="<?= $_POST['Term'] ?>">
  
<?php
  //Print column headers
  foreach($column_names as $key => $long_name)
  {
?>
    <th><button class="inlinebutton" type="submit" name="SortBy" value="<?= $key ?>">
    <?php if($_POST['SortBy'] == $key): ?>
      <span style="text-decoration:underline"><?= $long_name ?></span><?= $sorting_arrow ?>
    <?php else: ?>
      <?= $long_name ?>
    <?php endif; ?>
    </button></th>
<?php
  }
?>
  
</form>

<?php
  // mysqli_fetch_array will return a row of data from the query
  // until no further data is available
  $isEmpty = true;
  while($row = mysqli_fetch_array($response))
  {
    if($row['Score'] == NULL)
      $realScore = "N.A.";
    else
      $realScore = $row['Score'];
?>
    
    <tr>
      <td>
        <button
          class="inlinebutton iconbutton selectButton"
          style="font-size:30"
          Rname="<?= $row['Name'] ?>"
          selectionCallbackFunction=getSelectionStatusAndUpdateElement
          removeText="☑" addText="☐">
        </button>
        <script>
          //Call getSelectionStatus once for each selectButton to set the initial element text
          functions["getSelectionStatusAndUpdateElement"].call(document.querySelector("#selectButtonContainer .selectButton[Rname=\"<?= $dbc->escape_string($row['Name']) ?>\"]"));
        </script>
      </td>
      
      <td>
        <form action="viewrecipe.php" method="post">
          <input class="inlinebutton" style="text-decoration:underline;" type="submit" name="Name" value= "<?= $row['Name'] ?>">
        </form>
      </td>
      
      <td><?= $row['Course'] ?></td>
      <td><?= $row['Instrument'] ?></td>
      <td><?= $row['Prep_time'] ?></td>
      <td><?= $row['Cook_time'] ?></td>
      <td><?= $realScore ?></td>
    </tr>
    
<?php
    $isEmpty = false;
  } // end while loop that iterates over query results
?>

</table>

<?php
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

</body>