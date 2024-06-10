<head>
<title>Sidebar Buttons</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="container">
<div class="box">
<div class="box-row">
<div class="box-cell edges" style="width: 100%">
    <h2>Recipe Database</h2>
    <button class="menubutton" onclick="window.parent.location.href='addrecipe.php';"><b><span class="icon">➕</span>Add New Recipe</b></button>
    <button class="menubutton" onclick="window.parent.location.href='getrecipeinfo.php';"><b><span class="icon">🔍</span>Search</b></button>
    <button class="menubutton" onclick="window.parent.location.href='selection.php';"><b><span class="icon">☰</span>Manage Selection</b></button>
    <button style="position: absolute; bottom: 13%; left: 0; background: none; font-size: 20;" onclick="window.parent.location.href='http://localhost/phpmyadmin/db_structure.php?server=1&db=recipedb';"><b><span class="icon">⚙</span></b></button>
</div>	
</div>	
</div>	
</div>	
</body>