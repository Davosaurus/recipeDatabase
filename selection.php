<head>
<title>Grocery List</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="selectaction.js"></script>
</head>

<body id=selectButtonContainer>

<?php
include("global.php");
global $placeholderInameInvisible;
global $allRname;

// Get a connection for the database
require_once('mysqli_connect.php');
?>

<iframe class="menubox main" src="sidebar.php"></iframe>
<div class="sidebar menubox context">
  <button
    class="menubutton"
    Rname="<?= $allRname ?>"
    nextSelectMethod=remove
    selectionCallbackFunction=reloadPage
    onclick="
      if(confirm('Are you sure? This will de-select all recipes!'))
        setSelectionStatus.call(this);
    ">
    Remove All
  </button>
</div>
<div class="center">

<?php
$query = "SELECT DISTINCT Rname
          FROM Selection;";
$info = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($info)
{
?>

<table style="table-layout:fixed; width:100%;">
  <tr>
    <th style="width: 30%"><h1 class="header">Selected Recipes</h1></th>
    <th><h1 class="header">Grocery List</h1></th>
  </tr>
  <tr>
    <td style="vertical-align:top">
    
      <table cellspacing="5" cellpadding="8">
        <tr>
          <td />
          <td><b>Recipe Name</b></td>
        </tr>

<?php
  $isEmpty = true;
  while($row = mysqli_fetch_array($info))
  {
?>

        <tr>
          <td>
            <button
              class="inlinebutton iconbutton selectButton"
              Rname="<?= $row['Rname'] ?>"
              nextSelectMethod=remove
              selectionCallbackFunction=reloadPage>
              ❌
            </button>
          </td>
          <td>
            <form action="viewrecipe.php" method="post">
              <input class="inlinebutton" style="text-decoration:underline;" type="submit" name="Name" value= "<?= $row['Rname'] ?>">
            </form>
          </td>
        </tr>

<?php
    $isEmpty = false;
  } // end while loop that iterates over query results
?>

      </table>
      
    </td>

<?php
}
else
{
  echo "Couldn't issue database query<br />";
  echo mysqli_error($dbc);
}

$query = "SELECT Rname, Iname, Amount, Unit
          FROM Selection
          ORDER BY Iname;";
$info = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($info)
{
?>

    <td style="vertical-align:top">
  
      <table cellspacing="5" cellpadding="8">
        <tr>
          <td />
          <td><b>Ingredient</b></td>
          <td><b>Amount</b></td>
        </tr>

<?php
  while($row = mysqli_fetch_array($info))
  {
    if($row['Iname'] != $placeholderInameInvisible)
    {
?>

        <tr>
          <td>
            <button
              class="inlinebutton iconbutton selectButton"
              Rname="<?= $row['Rname'] ?>"
              Iname="<?= $row['Iname'] ?>"
              nextSelectMethod=remove
              selectionCallbackFunction=deleteCallingElement>
              ❌
            </button>
          </td>
          <td><?= $row['Iname'] ?></td>
          <td style="text-align: right"><?= $row['Amount'] ?></td>
          <td><?= $row['Unit'] ?></td>
        </tr>

<?php
    }
  }
?>

      </table>
      
    </td>
  </tr>
</table>
  
<?php
  if($isEmpty)
    echo '<tr><td>No recipes selected!</td></tr>';
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
