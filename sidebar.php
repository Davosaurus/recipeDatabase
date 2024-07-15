<head>
<title>Sidebar Main Menu Buttons</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="sidebar">
  <h2>Recipe Database</h2>
  <button class="menubutton" onclick="window.parent.location.href='addrecipe.php';"><span class="icon">➕</span>Add New Recipe</button>
  <button class="menubutton" onclick="window.parent.location.href='getrecipeinfo.php';"><span class="icon">🔍</span>Search</button>
  <button class="menubutton" onclick="window.parent.location.href='selection.php';"><span class="icon">☰</span>Manage Selection</button>
  <button style="position: absolute; bottom: 0; left: 0; background: none; font-size: 20; font-weight: bold" onclick="window.open('http://localhost/phpmyadmin/db_structure.php?server=1&db=recipedb');"><span class="icon">⚙</span></button>
</body>