<?php

$placeholderInameInvisible = "__NULL_INAME_INVISIBLE__";
$allRname = "__ALL__";

$units = array(
  "Teaspoons" => array(
    "type" => "volume",
    "size" => 768
  ),
  "Tablespoons" => array(
    "type" => "volume",
    "size" => 256
  ),
  "Fluid Ounces" => array(
    "type" => "volume",
    "size" => 128
  ),
  "Cups" => array(
    "type" => "volume",
    "size" => 16
  ),
  "Pints" => array(
    "type" => "volume",
    "size" => 8
  ),
  "Quarts" => array(
    "type" => "volume",
    "size" => 4
  ),
  "Gallons" => array(
    "type" => "volume",
    "size" => 1
  ),
  "Ounces" => array(
    "type" => "weight",
    "size" => 16
  ),
  "Pounds" => array(
    "type" => "weight",
    "size" => 1
  ),
  "Cans" => array(
    "type" => "can",
    "size" => 1
  ),
  "Units/Packages" => array(
    "type" => "singleton",
    "size" => 1
  )
);

function clearStoredResults()
{
  global $dbc;
  
  do
  {
    if ($res = $dbc->store_result())
    {
      $res->free();
    }
  } while ($dbc->more_results() && $dbc->next_result());
}

function combineIngredients($ingredientSet, $newIngredient)
{
  global $units;
  global $placeholderInameInvisible;
  
  $newUnit = $units[$newIngredient["Unit"]];
  
  if($ingredientSet == $placeholderInameInvisible)
  {
    $ingredientSet = array($newUnit["type"] => $newIngredient);
    $ingredientSet[$newUnit["type"]]["Rname"] = array($newIngredient["Rname"]);
    return $ingredientSet;
  }
  
  //If new ingredient type is already present in the map
  if(isset($ingredientSet[$newUnit["type"]]))
  {
    $originalUnit = $units[$ingredientSet[$newUnit["type"]]["Unit"]];

    //Convert amount to the bigger of the two units
    if($newUnit["size"] < $originalUnit["size"])
    {
      //Set amount
      //            combined amount              =         new amount       + (              original amount              *          ratio between units            )
      $ingredientSet[$newUnit["type"]]["Amount"] = $newIngredient["Amount"] + ($ingredientSet[$newUnit["type"]]["Amount"] * $newUnit["size"] / $originalUnit["size"]);

      //Set unit
      $ingredientSet[$newUnit["type"]]["Unit"] = $newIngredient["Unit"];
    }
    else
    {
      //Set amount
      //            combined amount              =              original amount               + (         new amount      *          ratio between units            )
      $ingredientSet[$newUnit["type"]]["Amount"] = $ingredientSet[$newUnit["type"]]["Amount"] + ($newIngredient["Amount"] * $originalUnit["size"] / $newUnit["size"]);

      //Leave unit as original
    }
    
    //Set Rname
    $ingredientSet[$newUnit["type"]]["Rname"][] = $newIngredient["Rname"];
  }
  else
  {
    $ingredientSet[$newUnit["type"]] = $newIngredient;
    $ingredientSet[$newUnit["type"]]["Rname"] = array($newIngredient["Rname"]);
  }

  return $ingredientSet;
}

?>