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
  <iframe class="iframe box-cell edges" src="sidebar.php"></iframe>
  <div class="box-cell center">

<?php
// Get a connection for the database
require_once('mysqli_connect.php');

if(isset($_POST['DeleteAll']))
{
  $query = "DELETE FROM Selection";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entries<br />";
    echo mysqli_error($dbc);
  }
}
if(isset($_POST['DeleteIname']))
{
  $query = "DELETE FROM Selection
            WHERE Rname = \"".$dbc->escape_string($_POST['DeleteRname'])."\"
            AND Iname = \"".$dbc->escape_string($_POST['DeleteIname'])."\";";
  if(!@mysqli_query($dbc, $query))
  {
    echo "Couldn't delete database entries<br />";
    echo mysqli_error($dbc);
  }
}
else
{
	if(isset($_POST['DeleteRname']))
	{
	  $query = "DELETE FROM Selection
				WHERE Rname = \"".$dbc->escape_string($_POST['DeleteRname'])."\";";
	  if(!@mysqli_query($dbc, $query))
	  {
		echo "Couldn't delete database entries<br />";
		echo mysqli_error($dbc);
	  }
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

  <tr><td colspan="2"><b>Recipe Name</b></td></tr>';

  $isEmpty = true;
  while($row = mysqli_fetch_array($info))
  {
    echo '<tr>
	<td style="padding: 2 12 0 0">
    <form style="display:inline" action="selection.php" method="post">
      <button type="submit" name="DeleteRname" value="'.$row['Rname'].'"><b>❌</b></button>
    </form></td>
	<td style="padding: 2 12 0 0"><form action="viewrecipe.php" method="post">
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

$query = "SELECT Rname, Iname, Amount, Unit
          FROM Selection
          ORDER BY Iname;";
$info = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($info)
{ 
  echo '<td id="printMe"><table align="left"
  cellspacing="5" cellpadding="8">
  <tr><td style="padding: 2 12 0 0"></td>
  <td style="padding: 12 12 0 0"><b>Ingredient</b></td>
  <td style="padding: 12 2 0 0"><b>Amount</b></td></tr>';
  while($row = mysqli_fetch_array($info))
  {
    echo '<tr><td style="padding: 2 12 0 0">
    <form style="display:inline" action="selection.php" method="post">
      <input type="hidden" name="DeleteRname" value="'.$row['Rname'].'"/>
      <button type="submit" name="DeleteIname" value="'.$row['Iname'].'"><b>❌</b></button>
    </form></td>
    <td style="padding: 2 12 0 0">'.$row['Iname'].'</td>
    <td style="padding: 2 2 0 0; text-align: right">'.$row['Amount'].'</td>
    <td style="padding: 2 12 0 0">'.$row['Unit'].'</td></tr>';
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
    <form style="display:inline" action="#" method="post"><button class="menubutton right" onclick="return printDiv()" name="Print" value="foo"><b>Print List</b></button></form>
    <form style="display:inline" action="selection.php" method="post" onsubmit="return confirm('Are you sure? This will de-select all recipes!');"><button class="menubutton right" type="submit" name="DeleteAll" value="DeleteAll"><b>Remove All</b></button></form>
  </div>

</div></div></div>

</body>