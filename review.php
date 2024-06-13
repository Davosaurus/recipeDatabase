<head>
<title>Review Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="container"><div class="box"><div class="box-row">
  <iframe class="iframe box-cell edges" src="sidebar.php"></iframe>
  <div class="box-cell center">

<form action="viewrecipe.php" method="post">

<?php
echo '<h1 class="header">Review '.$_POST['Name'].'</h1>';

echo '<p><b>Reviewer:</b><br>
<input type="text" name="Reviewer" style="width:300" value="" placeholder="Your name here"/>
</p>

<p><b>Taste:</b><br>
<input type="number" name="Taste" style="width:300" value="" placeholder="Integer from 0-10 (10 being best)" min="0" max="10"/>
</p>

<p><b>Cost Efficiency:</b><br>
<input type="number" name="Cost" style="width:300" value="" placeholder="Integer from 0-10 (10 being best)" min="0" max="10"/>
</p>

<input type="hidden" name="Name" value="'.$_POST['Name'].'">';
?>

  </div>
  <div class="box-cell edges">
    <h2>Osterman 2019</h2>
  <button class='menubutton right' type='submit' name='Review' value='Submit'><b>Submit Review</b></button>
  </div>
</form>
</div></div></div>

</body>