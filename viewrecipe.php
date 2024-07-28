<head>
<title>View Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="selectaction.js"></script>
</head>

<body>

<?php
include("global.php");

// Get a connection for the database
require_once('mysqli_connect.php');
?>

<iframe class="menubox main" src="sidebar.php"></iframe>
<div id=selectButtonContainer class="sidebar menubox context">
  <button
    class="menubutton selectButton"
    Rname="<?= $_POST['Name'] ?>"
    selectionCallbackFunction=getSelectionStatusAndUpdateElement
    removeText="Remove from<br>Selection" addText="Add to Selection">
  </button>
  <script>
    //Call getSelectionStatus once for each selectButton to set the initial element text
    functions["getSelectionStatusAndUpdateElement"].call(document.querySelector("#selectButtonContainer .selectButton[Rname=\"<?= $dbc->escape_string($_POST['Name']) ?>\"]"));
  </script>

  <form action="review.php" method="post">
    <button class="menubutton" type="submit" name="Name" value="<?= $_POST['Name'] ?>">
      Write Review
    </button>
  </form>
  <form action="getrecipeinfo.php" method="post" onsubmit="return confirm('Are you sure? This will permanently delete this recipe!');">
    <button class="menubutton" type="submit" name="Delete" value="<?= $_POST['Name'] ?>">
      Delete Recipe
    </button>
  </form>
</div>
<div class="center">

<?php
// If a new review is being posted
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
    echo 'Error occured! ';
    echo mysqli_error($dbc);
  }
}

// Get aggregate review data
$scoreQuery = "SELECT AVG(Taste) AS 'taste', AVG(Cost) AS 'cost' FROM Review WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";
$score = mysqli_fetch_assoc(@mysqli_query($dbc, $scoreQuery));

if(isset($_POST['Review']))
{
  // Update average score in recipe table
  $combinedScore = ( $score['taste'] + $score['cost'] ) / 2;
  $scoreUpdateQuery = "UPDATE Recipe SET Score = ".$combinedScore." WHERE Name = \"".$dbc->escape_string($_POST['Name'])."\"";
  @mysqli_query($dbc, $scoreUpdateQuery);
}

$query = "SELECT Rname, Iname, Amount, Unit
          FROM Ingredient
          WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";

$ingredients = @mysqli_query($dbc, $query);

if($ingredients)
{
  while($row = mysqli_fetch_assoc($ingredients))
  {
    $ingredient_list[] = $row['Iname'];
    $amount_list[] = $row['Amount'];
    $unit_list[] = $row['Unit'];
  }
}

$query = "SELECT Name, Course, Instrument, Prep_time, Cook_time, Score
          FROM Recipe
          WHERE Name = \"".$dbc->escape_string($_POST['Name'])."\";";
$info = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($info)
{
  $info = mysqli_fetch_assoc($info);
?>

<h1 class="header"><?= $info['Name'] ?></h1>
  
<!-- Table that contains info (first row), ingredients table (second row), and instructions table (second row, second column) -->
<table>
  <tr><!-- First page row -->
    <td style="width:50%">

      <table>
        <tr>
          <td>
            <?= $info['Course'] ?><br>
            <?= $info['Instrument'] ?><br>
            <b>Prep Time: </b><?= $info['Prep_time'] ?> minutes<br>
            <b>Cook Time: </b><?= $info['Cook_time'] ?> minutes<br>

<?php
            if($score['taste'] != NULL)
              echo '<b>Taste Score: </b>'.round($score['taste'], 1).'<br>';

            if($score['cost'] != NULL)
              echo '<b>Cost Efficiency Score: </b>'.round($score['cost'], 1).'<br>';
?>

          </td>
        </tr>
      </table>
  
    </td>
  </tr>
  <tr height=30 /><!-- Spacer row -->

<?php
}
else
{
  echo "Couldn't issue database query<br>";
  echo mysqli_error($dbc);
}

// If the query executed properly proceed
if($ingredients)
{
?>

  <tr><!-- Second page row -->
    <td style="vertical-align:top">

      <table cellspacing="5" cellpadding="8">
        <tr>
          <td><b>Ingredient</b></td>
          <td style="text-align: right"><b>Amount</b></td>
        </tr>
        <?php for($i = 0; $i < sizeof($ingredient_list); $i++): ?>
          <tr>
            <td><?= $ingredient_list[$i] ?></td>
            <td style="text-align: right"><?= $amount_list[$i] ?></td>
            <td><?= $unit_list[$i] ?></td>
          </tr>
        <?php endfor; ?>
      </table>
      
    </td>

<?php
}
else
{
  echo "Couldn't issue database query<br>";
  echo mysqli_error($dbc);
}

$query = "SELECT Rname, Step_num, Step_instruction
          FROM Instruction
          WHERE Rname = \"".$dbc->escape_string($_POST['Name'])."\";";

$instructions = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($instructions)
{
?>

    <td style="vertical-align:top">
      
      <table cellspacing="5" cellpadding="8">
        <tr>
          <td />
          <td><b>Instructions</b></td>
        </tr>
        <?php while($row = mysqli_fetch_assoc($instructions)): ?>
          <tr>
            <td style="vertical-align:top"><?= $row['Step_num'] ?>)</td>
            <td><?= $row['Step_instruction'] ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
      
    </td>
  </tr>
</table>

<?php
}
else
{
  echo "Couldn't issue database query<br>";
  echo mysqli_error($dbc);
}

// Close connection to the database
mysqli_close($dbc);
?>

</div>

</body>