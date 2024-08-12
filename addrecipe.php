<head>
<title>Add Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
var addIngredientsTable;
var addInstructionsTable;
var remainingIngredients = [];
var remainingInstructions = [];

$(document).ready(function() {
  $("#addNewIngredient").on("click", (event) => addNewIngredient({ingredient: "", amount: "", unit: ""}));
  $("#addNewInstruction").on("click", (event) => addNewInstruction({instruction: ""}));
  $("#removeIngredient").on("click", removeIngredient);
  $("#removeInstruction").on("click", removeInstruction);
  
  addIngredientsTable = document.getElementById("addIngredients");
  addInstructionsTable = document.getElementById("addInstructions");
  
  remainingIngredients.forEach(addNewIngredient);
  remainingInstructions.forEach(addNewInstruction);
});

function addNewIngredient(param) {
  var originalIngredientRow = addIngredientsTable.querySelector("#initialIngredientRow");
  var newIngredientRow = originalIngredientRow.cloneNode(true);
  var nextIngredientNum = addIngredientsTable.querySelectorAll("tr").length;

  var newIngredient = newIngredientRow.querySelector(".ingredient");
  newIngredient.name = "Ingredient_" + nextIngredientNum;
  newIngredient.value = param.ingredient;
  var newAmount = newIngredientRow.querySelector(".amount");
  newAmount.name = "Amount_" + nextIngredientNum;
  newAmount.value = param.amount;
  var newUnit = newIngredientRow.querySelector(".unit");
  newUnit.name = "Unit_" + nextIngredientNum;
  newUnit.value = param.unit;
  newIngredientRow.id = "newIngredientRow_" + nextIngredientNum;

  addIngredientsTable.querySelector("tbody").appendChild(newIngredientRow);
}

function addNewInstruction(param) {
  var originalInstructionRow = addInstructionsTable.querySelector("#initialInstructionRow");
  var newInstructionRow = originalInstructionRow.cloneNode(true);
  var nextInstructionNum = addInstructionsTable.querySelectorAll("tr").length;
  
  var newInstruction = newInstructionRow.querySelector(".instruction");
  newInstruction.name = "Instruction_" + nextInstructionNum;
  newInstruction.value = param.instruction;
  newInstructionRow.id = "newInstructionRow_" + nextInstructionNum;

  addInstructionsTable.querySelector("tbody").appendChild(newInstructionRow);
  autoGrow(newInstruction);
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

function autoGrow(element) {
  element.style.height = "5px";
  element.style.height = (element.scrollHeight + 5) + "px";
}
</script>

</head>


<body>

<?php

require_once('mysqli_connect.php');

$pageHeader = "Add a New Recipe";
$submitConfirmMessage = "";
$name = "";
$course = "";
$instrument = "";
$prepTime = "";
$cookTime = "";
$initialIngredient = "";
$initialAmount = "";
$initialUnit = "";
$initialInstruction = "";

if(isset($_POST['Edit']))
{
  $pageHeader = "Edit Recipe";
  
  
  $query = "SELECT DISTINCT Rname
            FROM Selection
            WHERE Rname = '".$dbc->escape_string($_POST['Edit'])."'";
  $selection = @mysqli_query($dbc, $query);

  // If the query executed properly proceed
  if($selection)
  {
    if(mysqli_num_rows($selection) > 0)
    {
      $submitConfirmMessage = "return confirm('Notice: recipe will be removed from your selection.');";
    }
  }
  else
  {
    echo "Couldn't issue 'selection' database query<br>";
    echo mysqli_error($dbc);
  }
}

?><form action="recipeadded.php" method="post" onsubmit="<?= $submitConfirmMessage ?>"><?php

if(isset($_POST['Edit']))
{
  $query = "SELECT Name, Course, Instrument, Prep_time, Cook_time, Score
            FROM Recipe
            WHERE Name = \"".$dbc->escape_string($_POST['Edit'])."\";";
  $info = @mysqli_query($dbc, $query);

  // If the query executed properly proceed
  if($info)
  {
    $info = mysqli_fetch_assoc($info);
    
    $name = $info['Name'];
    $course = $info['Course'];
    $instrument = $info['Instrument'];
    $prepTime = $info['Prep_time'];
    $cookTime = $info['Cook_time'];
    if(isset($info['Score']))
    {
      ?><input type="hidden" name="Score" value="<?= $info['Score'] ?>"><?php
    }
  }
  else
  {
    echo "Couldn't issue 'recipe' database query<br>";
    echo mysqli_error($dbc);
  }
  
  
  $query = "SELECT Rname, Iname, Amount, Unit
            FROM Ingredient
            WHERE Rname = \"".$dbc->escape_string($_POST['Edit'])."\";";
  $ingredients = @mysqli_query($dbc, $query);

  if($ingredients)
  {
    $row = mysqli_fetch_assoc($ingredients);
    $initialIngredient = $row['Iname'];
    $initialAmount = $row['Amount'];
    $initialUnit = $row['Unit'];
    
    while($row = mysqli_fetch_assoc($ingredients))
    {
      ?><script>
        remainingIngredients.push({
          ingredient: "<?= $row['Iname'] ?>",
          amount: "<?= $row['Amount'] ?>",
          unit: "<?= $row['Unit'] ?>"
        });
      </script><?php
    }
  }
  else
  {
    echo "Couldn't issue 'ingredients' database query<br>";
    echo mysqli_error($dbc);
  }
  
  
  $query = "SELECT Rname, Step_num, Step_instruction
            FROM Instruction
            WHERE Rname = \"".$dbc->escape_string($_POST['Edit'])."\";";
  $instructions = @mysqli_query($dbc, $query);

  // If the query executed properly proceed
  if($instructions)
  {
    $row = mysqli_fetch_assoc($instructions);
    $initialInstruction = $row['Step_instruction'];
    
    while($row = mysqli_fetch_assoc($instructions))
    {
      ?><script>
        remainingInstructions.push({
          instruction: "<?= $row['Step_instruction'] ?>"
        });
      </script><?php
    }
  }
  else
  {
    echo "Couldn't issue 'instructions' database query<br>";
    echo mysqli_error($dbc);
  }

  ?><input type="hidden" name="Edit" value="<?= $_POST['Edit'] ?>"><?php

  // Close connection to the database
  mysqli_close($dbc);
}

?>

<iframe class="menubox main" src="sidebar.php"></iframe>
<div class="sidebar menubox context">
  <button class="menubutton" type="submit" name="submit" value="Submit">Submit Recipe</button>
</div>
<div class="center">

<h1 class="header"><?= $pageHeader ?></h1>

<p><b>Recipe Name:</b><br>
<input type="text" name="Name" size="30" maxlength="100" value="<?= $name ?>" autofocus="autofocus" required>
</p>

<p><b>Dish Type:</b><br>
<input type="text" name="Course" size="30" maxlength="25" value="<?= $course ?>" placeholder="e.g. entree, appetizer, beverage" required>
</p>

<p><b>Cooking Instrument:</b><br>
<input type="text" name="Instrument" size="30" maxlength="25" value="<?= $instrument ?>" placeholder="e.g. grill, stovetop, oven" required>
</p>

<p><b>Prep. Time (minutes):</b><br>
<input type="number" name="Prep_time" style="width:50" value="<?= $prepTime ?>" min="0" required>
</p>

<p><b>Cook Time (minutes):</b><br>
<input type="number" name="Cook_time" style="width:50" value="<?= $cookTime ?>" min="0" required>
</p>

<table id="addIngredients" style="padding: 8px 20px 8px 0; margin:0">
  <tr><td><b>Ingredients:</b></td>
  <td colspan=2><b>Amount:</b></td></tr>
  <tr id=initialIngredientRow>
    <td><input class=ingredient type="text" name="Ingredient_1" size="45" maxlength="250" value="<?= $initialIngredient ?>" required> </td>
    <td><input class=amount type="number" name="Amount_1" style="width:50" value="<?= $initialAmount ?>" min="0" step="any" required> </td>
    <td>
      <select class=unit name="Unit_1" required>
        <option disabled selected value="">Choose unit...</option>
        <?php
          include("global.php");
          global $units;
          foreach($units as $unit => $value)
          {
            echo '<option';
            if($unit == $initialUnit)
              echo ' selected';
            echo '>'.$unit.'</option>';
          }
        ?>
      </select>
    </td>
  </tr>
</table>
<div>
  <div style="width: 50; display: inline-block"><button id="removeIngredient" class="menubutton" style="text-align:center" type="button">➖</button></div>
  <div style="width: 530; display: inline-block"><button id="addNewIngredient" class="menubutton" style="text-align:center" type="button">➕</button></div>
</div>

<table id="addInstructions" style="padding: 8px 20px 8px 0; margin:0">
  <tr><td><b>Instructions:</b></td></tr>
  <tr id=initialInstructionRow>
    <td>
      <textarea class=instruction type=text name=Instruction_1 maxlength=500 style=width:590 oninput="autoGrow(this)" required><?= $initialInstruction ?></textarea>
        <script>
          //Call autoGrow once for the first instruction to set the initial element height
          autoGrow(document.querySelector("#initialInstructionRow .instruction"));
        </script>
    </td>
  </tr>
</table>
<div>
  <div style="width: 50; display: inline-block"><button id="removeInstruction" class="menubutton" style="text-align:center" type="button">➖</button></div>
  <div style="width: 530; display: inline-block"><button id="addNewInstruction" class="menubutton" style="text-align:center" type="button">➕</button></div>
</div>

</div>
</form>

</body>