<head>
<title>Review Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<form action="viewrecipe.php" method="post">

<iframe class="menubox main" src="sidebar.php"></iframe>
<div class="sidebar menubox context">
  <button class='menubutton' type='submit' name='Review' value='Submit'>Submit Review</button>
</div>
<div class="center">

<h1 class="header">Review <?= $_POST['Name'] ?></h1>

<p><b>Reviewer:</b><br>
<input type="text" name="Reviewer" style="width:300" value="" placeholder="Your name here"/>
</p>

<p><b>Taste:</b><br>
<input type="number" name="Taste" style="width:300" value="" placeholder="Integer from 0-10 (10 being best)" min="0" max="10"/>
</p>

<p><b>Cost Efficiency:</b><br>
<input type="number" name="Cost" style="width:300" value="" placeholder="Integer from 0-10 (10 being best)" min="0" max="10"/>
</p>

<input type="hidden" name="Name" value="<?= $_POST['Name'] ?>">

</div>
</form>
</body>