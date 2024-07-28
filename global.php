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
    "type" => "unit",
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

function combineIngredients($original, $new)
{
  if($original == null)
  {
    return $new;
  }

  //Are the two units compatible?
  //Find the bigger unit between the two
  //Convert the other amount into the bigger unit
  //Add the two, set the result along with the new unit
  
  $original['Rname'] .= ', '.$new['Rname'];

  return $original;
}

?>