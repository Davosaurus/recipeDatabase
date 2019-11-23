<head>
<title>Grocery List</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script>
function printDiv() {
  var divToPrint=document.getElementById('printMe');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
}
</script>
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

if(isset($_POST['Delete']))
{
  $query = "DELETE FROM Selection";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entries<br />";
    echo mysqli_error($dbc);
  }
}

$query = "SELECT DISTINCT Rname
          FROM Selection;";
$info = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($info)
{
  echo '<table><tr><td style="min-width:400px"><h1 class="header">Selected Recipes</h1></td>
  <td><h1 class="header">Grocery List</h1></td></tr>';
  
  echo '<tr><td><table align="left"
  cellspacing="5" cellpadding="8">

  <tr><td><b>Recipe Name</b></td></tr>';

  $isEmpty = true;
  while($row = mysqli_fetch_array($info))
  {
    echo '<tr><td><form action="viewrecipe.php" method="post">
    <input style="background:none; color:inherit; font:inherit; text-align:left; border:none; cursor:pointer; text-decoration:underline; padding:0; word-break:break-word; white-space:normal; max-width: 400px;" type="submit" name="Name" value="'.$row['Rname'].'" />
    </form></td></tr>';
    $isEmpty = false;
  }
  echo '</table></td>';
}
else
{
  echo "Couldn't issue database query<br />";
  echo mysqli_error($dbc);
}

$query = "SELECT Rname, Iname, Amount
          FROM Selection;";
$info = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($info)
{
  
  echo '<td id="printMe"><table align="left"
  cellspacing="5" cellpadding="8">

  <tr><td><b>Ingredient</b></td>
  <td><b>Amount</b></td></tr>';

  $ingredient_list = [];
  while($row = mysqli_fetch_array($info))
  {
    if(array_key_exists($row['Iname'], $ingredient_list))
      $ingredient_list[$row['Iname']] += $row['Amount'];
    else
      $ingredient_list[$row['Iname']] = $row['Amount'];
  }
  
  foreach($ingredient_list as $Iname => $Amount)
  {
    echo '<tr><td>'.$Iname.'</td><td>'.$Amount.'</td>';
    echo '</tr>';
  }
  echo '</table></td></tr></table>';
  
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
  <div class="box-cell edges">
    <h2>Osterman 2019</h2>
    <form style="display:inline" action="#" method="post"><button class="menubutton" onclick="return printDiv()" name="Print" value="foo"><b>Print List</b></button></form>
    <form style="display:inline" action="selection.php" method="post" onsubmit="return confirm('Are you sure? This will de-select all recipes!');"><button class="menubutton" type="submit" name="Delete" value="Delete"><b>Mark as Bought</b></button></form>
  </div>

</div></div></div>

</body>