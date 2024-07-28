<head>
<title>Add Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
var addIngredientsTable;
var addInstructionsTable;

$(document).ready(function() {
  $("#addNewIngredient").on("click", addNewIngredient);
  $("#addNewInstruction").on("click", addNewInstruction);
  $("#removeIngredient").on("click", removeIngredient);
  $("#removeInstruction").on("click", removeInstruction);
  
  addIngredientsTable = document.getElementById("addIngredients");
  addInstructionsTable = document.getElementById("addInstructions");
});

function addNewIngredient() {
  var originalIngredientRow = addIngredientsTable.querySelector("#initialIngredientRow");
  var newIngredientRow = originalIngredientRow.cloneNode(true);
  var nextIngredientNum = addIngredientsTable.querySelectorAll("tr").length;

  var newIngredient = newIngredientRow.querySelector(".ingredient");
  newIngredient.name = "Ingredient_" + nextIngredientNum;
  newIngredient.value = "";
  var newAmount = newIngredientRow.querySelector(".amount");
  newAmount.name = "Amount_" + nextIngredientNum;
  newAmount.value = "";
  newIngredientRow.querySelector(".unit").name = "Unit_" + nextIngredientNum;
  newIngredientRow.id = "newIngredientRow_" + nextIngredientNum;

  addIngredientsTable.querySelector("tbody").appendChild(newIngredientRow);
}

function addNewInstruction() {
  var originalInstructionRow = addInstructionsTable.querySelector("#initialInstructionRow");
  var newInstructionRow = originalInstructionRow.cloneNode(true);
  var nextInstructionNum = addInstructionsTable.querySelectorAll("tr").length;
  
  var newInstruction = newInstructionRow.querySelector(".instruction");
  newInstruction.name = "Instruction_" + nextInstructionNum;
  newInstruction.value = "";
  newInstructionRow.id = "newInstructionRow_" + nextInstructionNum;

  addInstructionsTable.querySelector("tbody").appendChild(newInstructionRow);
}

function removeIngredient() {
  var lastIngredientNum = addIngredientsTable.querySelectorAll("tr").length - 1;
  var rowToDelete = addIngredientsTable.querySelector("#newIngredientRow_" + lastIngredientNum);
  
  if(rowToDelete != null) {
    rowToDelete.remove();
  }
}

function removeInstruction() {
  var lastInstructionNum = addInstructionsTable.querySelectorAll("tr").length - 1;
  var rowToDelete = addInstructionsTable.querySelector("#newInstructionRow_" + lastInstructionNum);
  
  if(rowToDelete != null) {
    rowToDelete.remove();
  }
}
</script>

</head>


<body>

<form action="recipeadded.php" method="post">

<iframe class="menubox main" src="sidebar.php"></iframe>
<div class="sidebar menubox context">
  <button class="menubutton" type="submit" name="submit" value="Submit">Submit Recipe</button>
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
  <tr id=initialIngredientRow>
    <td><input class=ingredient type="text" name="Ingredient_1" size="45" maxlength="250" value="" required/> </td>
    <td><input class=amount type="number" name="Amount_1" style="width:50" value="" min="0" step="any" required/> </td>
    <td>
      <select class=unit name="Unit_1" value="" required>
        <option disabled selected value="">Choose unit...</option>
        <?php
          include("global.php");
          global $units;
          foreach($units as $unit => $value)
          {
            echo '<option>'.$unit.'</option>';
          }
        ?>
      </select>
    </td>
  </tr>
</table>
<div style="width: 543">
  <div style="width: 50; display: inline-block"><button id="removeIngredient" class="menubutton" style="text-align:center" type="button">➖</button></div>
  <div style="width: 483; display: inline-block"><button id="addNewIngredient" class="menubutton" style="text-align:center" type="button">➕</button></div>
</div>

<table id="addInstructions" style="padding: 8px 20px 8px 0; margin:0">
  <tr><td><b>Instructions:</b></td></tr>
  <tr id=initialInstructionRow>
    <td><input class=instruction type="text" name="Instruction_1" size="73" maxlength="500" value="" required/> </td>
  </tr>
</table>
<div style="width: 543">
  <div style="width: 50; display: inline-block"><button id="removeInstruction" class="menubutton" style="text-align:center" type="button">➖</button></div>
  <div style="width: 483; display: inline-block"><button id="addNewInstruction" class="menubutton" style="text-align:center" type="button">➕</button></div>
</div>

  </div>
</form>

</body>