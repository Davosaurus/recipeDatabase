<head>
<title>Add Recipe</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="container"><div class="box"><div class="box-row">
  <iframe class="box-cell edges" src="sidebar.php"></iframe>
  <div class="box-cell center">

<?php 
/*
echo '<table>';
    foreach ($_POST as $key => $value) {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>";
    }
echo '</table>';
*/
?>

<?php

if(isset($_POST['submit']))
{
  $data_missing = array();
  $Name = str_replace("\"", "''", trim($_POST['Name']));
  $Course = str_replace("\"", "''", trim($_POST['Course']));
  $Instrument = str_replace("\"", "''", trim($_POST['Instrument']));
  $Prep_time = trim($_POST['Prep_time']);
  $Cook_time = trim($_POST['Cook_time']);
  
  
  if(empty($_POST['Ingredient_1']))
    $data_missing[] = 'Ingredients';
  else
  {
    $i = 1;
    $moreIngredients = true;
    while($moreIngredients)
    {
      if(isset($_POST['Ingredient_'.$i]))
      {
        if(empty($_POST['Amount_'.$i]))
          $data_missing[] = 'Ingredient Amounts';
        if(empty($_POST['Unit_'.$i]))
          $data_missing[] = 'Ingredient Units';
        $ingredient_list[] = str_replace("\"", "''", trim($_POST['Ingredient_'.$i]));
        $amount_list[] = trim($_POST['Amount_'.$i]);
        $unit_list[] = trim($_POST['Unit_'.$i]);
        $i++;
      }
      else
        $moreIngredients = false;
    }
  }
  
  if(empty($_POST['Instruction_1']))
    $data_missing[] = 'Instructions';
  else
  {
    $i = 1;
    $moreInstructions = true;
    while($moreInstructions)
    {
      if(isset($_POST['Instruction_'.$i]))
      {
        $instruction_list[] = str_replace("\"", "''", trim($_POST['Instruction_'.$i]));
        $i++;
      }
      else
        $moreInstructions = false;
    }
  }
  
  
  if(empty($data_missing))
  {
    require_once('mysqli_connect.php');
    
    $query = "INSERT INTO Recipe (Name, Course, Instrument, Prep_time, Cook_time) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($dbc, $query);
    
    //i Integers
    //d Doubles
    //b Blobs
    //s Everything Else
    
    mysqli_stmt_bind_param($stmt, "sssii", $Name, $Course, $Instrument, $Prep_time, $Cook_time);
    
    mysqli_stmt_execute($stmt);
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    
    if($affected_rows == 1)
    {
      echo '<h1 class="header">Recipe Entered!</h1>';
      mysqli_stmt_close($stmt);
    
      //loop to add ingredients to database
      for($i = 0; $i < sizeof($ingredient_list); $i++)
      {
        $query = "INSERT INTO Ingredient (Rname, Iname, Amount, Unit) VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($dbc, $query);
        
        mysqli_stmt_bind_param($stmt, "ssds", $Name, $ingredient_list[$i], $amount_list[$i], $unit_list[$i]);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        
        if($affected_rows == 1)
        {
          mysqli_stmt_close($stmt);
        }
        else
        {
          echo 'Error Occurred<br />';
          echo mysqli_error($dbc);
          mysqli_stmt_close($stmt);
        }
      }
      
      //loop to add instructions to database
      for($i = 1; $i <= sizeof($instruction_list); $i++)
      {
        $query = "INSERT INTO Instruction (Rname, Step_num, Step_instruction) VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($dbc, $query);
        
        mysqli_stmt_bind_param($stmt, "sis", $Name, $i, $instruction_list[$i-1]);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        
        if($affected_rows == 1)
        {
          mysqli_stmt_close($stmt);
        }
        else
        {
          echo 'Error Occurred<br />';
          echo mysqli_error($dbc);
          mysqli_stmt_close($stmt);
        }
      }
    }
    else
    {
      echo 'Error Occurred<br />';
      echo mysqli_error($dbc);
      mysqli_stmt_close($stmt);
    }
    mysqli_close($dbc);
  }
  else
  {
    echo '<b>You need to enter the following data:</b><br />';
    foreach($data_missing as $missing)
      echo "$missing<br />";
  }
}

?>

  </div>
  <div class="box-cell edges">
    <h2>Osterman 2019</h2>
  </div>
</div></div></div>

</body>