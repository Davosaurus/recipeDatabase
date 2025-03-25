<html class="iframe">
<head>
<title>Recipe List</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="selectaction.js"></script>
</head>

<body>

<?php
include("global.php");
global $allRname;

//Get a connection for the database
require_once('mysqli_connect.php');

//Handle recipe deletion if requested
if(isset($_POST['Delete'])) {
  deleteBaseRecipe($_POST['Delete']);
  deleteRecipeSelection($_POST['Delete']);
  deleteRecipeReviews($_POST['Delete']);
}

//Create a query for the database
$query = "SELECT Name, Course, Instrument, Prep_time, Cook_time, Score FROM Recipe";

//Qualifies the query with search terms
$_POST['Term'] = str_replace("\"", "''", $_POST['Term']);
if($_POST['Term'] != "") {
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

//Qualifies the query with sorting method
$query .= " ORDER BY ".$_POST['SortBy']." ".$_POST['Dir'];

$response = @mysqli_query($dbc, $query);

//If the query executed properly proceed
if($response)
{

?>


<div class="center">
<table id=selectButtonContainer cellspacing="5" cellpadding="8" width=100%>
  <tr class="header">
    <th width=3%>
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
    "Name" => array("displayName" => "Recipe Name", "displayWidthPercentage" => ""),
    "Course" => array("displayName" => "Dish Type", "displayWidthPercentage" => "12"),
    "Instrument" => array("displayName" => "Appliance", "displayWidthPercentage" => "12"),
    "Prep_time" => array("displayName" => "Prep🕖", "displayWidthPercentage" => "10"),
    "Cook_time" => array("displayName" => "Cook🕖", "displayWidthPercentage" => "10"),
    "Score" => array("displayName" => "Rating", "displayWidthPercentage" => "10")
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

<form target=_top action="getrecipeinfo.php" method="post">
<input type="hidden" name="Dir" value="<?= $opposite_dir ?>">
<input type="hidden" name="SearchBy" value="<?= $_POST['SearchBy'] ?>">
<input type="hidden" name="Term" value="<?= $_POST['Term'] ?>">
  
<?php
  //Print column headers
  foreach($column_names as $field_name => $field_data)
  {
?>
    <th width=<?= $field_data["displayWidthPercentage"] ?>%>
      <button class="inlinebutton" type="submit" name="SortBy" value="<?= $field_name ?>">
    <?php if($_POST['SortBy'] == $field_name): ?>
      <span style="text-decoration:underline"><?= $field_data["displayName"] ?></span><?= $sorting_arrow ?>
    <?php else: ?>
      <?= $field_data["displayName"] ?>
    <?php endif; ?>
      </button>
    </th>
<?php
  }
?>
  
</form>

<?php
  // mysqli_fetch_assoc will return a row of data from the query
  // until no further data is available
  $isEmpty = true;
  while($row = mysqli_fetch_assoc($response))
  {
    if($row['Score'] == NULL)
      $realScore = "";
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
        <form target=_top action="viewrecipe.php" method="post">
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