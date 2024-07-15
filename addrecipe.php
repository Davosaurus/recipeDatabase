<head>
<title>Add Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
var addDiv = $('#addIngredients');
var i = $('#addIngredients tr').size();

$('#addNewIngredient').live('click', function() {
$('<tr><td><input type="text" id="p_new" size="45" maxlength="250" name="Ingredient_' + i +'" value="" required/></td><td><input type="number" id="p_new" style="width:50" name="Amount_' + i +'" value="" min="0" step="any" required/></td><td><select id="p_new" name="Unit_' + i +'" value="" required> <option disabled selected value="">Choose unit...</option> <option>Teaspoons</option> <option>Tablespoons</option> <option>Fluid Ounces</option> <option>Cups</option> <option>Pints</option> <option>Quarts</option> <option>Gallons</option> <option>Ounces</option> <option>Pounds</option> <option>Cans</option> <option>Units/Packages</option> </select></td>   </tr>').appendTo(addDiv);
i++;

//<td><a href="#" id="remNewIngredient">Remove</a></td>

return false;
});

$('#remNewIngredient').live('click', function() {
if( i > 2 ) {
$(this).parents('tr').remove();
i--;
}
return false;
});
});

</script>

<script type="text/javascript">
$(function() {
var addDiv = $('#addInstructions');
var i = $('#addInstructions tr').size();

$('#addNewInstruction').live('click', function() {
$('<tr id=""InstructionRow_' + i +'""><td><input type="text" id="Instruction_' + i +'" size="73" maxlength="500" name="Instruction_' + i +'" value="" required/></td>   </tr>').appendTo(addDiv);
i++;
//<td><a href="#" id="remNewInstruction">Remove</a></td>

//if( i > 2 )
//{$('#remNewInstruction_' + (i-1)).parents('td').remove();}
//<td><a href="#" id="remNewInstruction_' + i +'">Remove</a></td>

return false;
});

$("[id^=remNewInstruction_]").live('click', function() {
if( i > 2 ) {
$(this).parents('tr').remove();
//$('#remNewInstruction_' + (i-1)).parents('tr').remove();
$('<td><a href="#" id="remNewInstruction_' + i +'">Remove</a></td>').appendTo(('#Instruction_' + (i-1)).parents('tr'));
i--;
}
return false;
});
});

</script>

</head>


<body>

<form action="recipeadded.php" method="post">

<iframe class="menubox main" src="sidebar.php"></iframe>
<div class="sidebar menubox context">
  <button class='menubutton' type='submit' name='submit' value='Submit'>Submit Recipe</button>
</div>
<div class="center">

<h1 class="header">Add a New Recipe</h1>

<p><b>Recipe Name:</b><br>
<input type="text" name="Name" size="30" maxlength="100" value="" autofocus="autofocus" required/>
</p>

<p><b>Dish Type:</b><br>
<input type="text" name="Course" size="30" maxlength="25" value="" placeholder="e.g. entree, appetizer, beverage" required/>
</p>

<p><b>Cooking Instrument:</b><br>
<input type="text" name="Instrument" size="30" maxlength="25" value="" placeholder="e.g. grill, stovetop, oven" required/>
</p>

<p><b>Prep. Time (minutes):</b><br>
<input type="number" name="Prep_time" style="width:50" value="" min="0" required/>
</p>

<p><b>Cook Time (minutes):</b><br>
<input type="number" name="Cook_time" style="width:50" value="" min="0" required/>
</p>

<table id="addIngredients" style="padding: 8px 20px 8px 0; margin:0">
  <tr><td><b>Ingredients:</b></td>
  <td colspan=2><b>Amount:</b></td></tr>
  <tr>
    <td><input type="text" name="Ingredient_1" size="45" maxlength="250" value="" required/> </td>
    <td> <input type="number" name="Amount_1" style="width:50" value="" min="0" step="any" required/> </td>
    <td>
      <select name="Unit_1" value="" required>
        <option disabled selected value="">Choose unit...</option>
        <option>Teaspoons</option> <option>Tablespoons</option>
        <option>Fluid Ounces</option> <option>Cups</option>
        <option>Pints</option> <option>Quarts</option>
        <option>Gallons</option> <option>Ounces</option>
        <option>Pounds</option> <option>Cans</option>
        <option>Units/Packages</option>
      </select>
    </td>
  </tr>
</table>
<div style="width: 543"><button id="addNewIngredient" class="menubutton" style="text-align:center">➕</button></div>

<table id="addInstructions" style="padding: 8px 20px 8px 0; margin:0">
  <tr><td><b>Instructions:</b></td></tr>
  <tr><td><input type="text" name="Instruction_1" size="73" maxlength="500" value="" required/> </td></tr>
</table>
<div style="width: 543"><button id="addNewInstruction" class="menubutton" style="text-align:center">➕</button></div>

  </div>
</form>

</body>