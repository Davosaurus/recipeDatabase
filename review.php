<head>
<title>Review Recipe</title>
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

<form action="viewrecipe.php" method="post">

<?php
echo '<h1 class="header">Review '.$_POST['Name'].'</h1>';

echo '<p><b>Reviewer:</b><br>
<input type="text" name="Reviewer" size="30" value="" placeholder="Your name here"/>
</p>

<p><b>Taste:</b><br>
<input type="number" name="Taste" size="30" value="" placeholder="Integer from 1-10 (10 being best)" min="1" max="10"/>
</p>

<p><b>Cost Efficiency:</b><br>
<input type="number" name="Cost" size="30" value="" placeholder="Integer from 1-10 (10 being best)" min="1" max="10"/>
</p>

<input type="hidden" name="Name" value="'.$_POST['Name'].'">';
?>

  </div>
  <div class="box-cell edges">
    <h2>Osterman 2019</h2>
  <button class='menubutton' type='submit' name='Review' value='Submit'><b>Submit Review</b></button>
  </div>
</form>
</div></div></div>

</body>