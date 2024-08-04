<?php

include("global.php");

// Get a connection for the database
require_once('mysqli_connect.php');

if($_POST['method'] == 'query') {
  isInSelection();
}
else if($_POST['method'] == 'add') {
  addToSelection();
}
else if($_POST['method'] == 'remove') {
  removeFromSelection();
}



function isInSelection() {
  global $dbc;
  
  $query = "SELECT DISTINCT Rname
            FROM Selection";
  if(isset($_POST['Rname']))
  {
    $query .= " WHERE Rname = '".$dbc->escape_string($_POST['Rname'])."'";
  }
  $info = @mysqli_query($dbc, $query);

  // If the query executed properly proceed
  if($info)
  {
    header('Content-Type: application/json');
    die(json_encode(mysqli_fetch_all($info)));
  }
  else
  {
    echo "Could not fetch selection from database.\n";
    echo mysqli_error($dbc);
  }
}

function addToSelection() {
  global $dbc;
  global $placeholderInameInvisible;

  //Copy data from ingredient table to selection table
  $query = "INSERT INTO Selection
            SELECT * FROM Ingredient
            WHERE Rname = '".$dbc->escape_string($_POST['Rname'])."'";

  if(!@mysqli_query($dbc, $query))
  {
    echo "Could not add selection data to database.\n";
    echo mysqli_error($dbc);
  }
  
  //Add an invisible placeholder ingredient, so that when all normal ingredients are removed the recipe remains
  $query = "INSERT INTO Selection (Rname, Iname) VALUES ('".$dbc->escape_string($_POST['Rname'])."', '".$placeholderInameInvisible."')";
  
  if(!@mysqli_query($dbc, $query))
  {
    echo "Could not add selection placeholder to database.\n";
    echo mysqli_error($dbc);
  }

  header('Content-Type: application/json');
  die(json_encode(true));
}

function removeFromSelection() {
  global $dbc;
  global $allRname;
  
  $query = "DELETE FROM Selection";
  
  if(isset($_POST['Iname']) || (isset($_POST['Rname']) && $_POST['Rname'] != $allRname))
  {
    $query .= " WHERE ";
    
    if(isset($_POST['Rname']))
    {
      $query .= "Rname = '".$dbc->escape_string($_POST['Rname'])."'";
      if(isset($_POST['Iname']))
      {
        $query .= " AND ";
      }
    }
    
    if(isset($_POST['Iname']))
    {
      $query .= "Iname = '".$dbc->escape_string($_POST['Iname'])."'";
      $query .= "AND Unit = '".$dbc->escape_string($_POST['Unit'])."'";
    }
  }
  
  if(!@mysqli_query($dbc, $query))
  {
    echo "Could not delete database entries.\n";
    echo mysqli_error($dbc);
  }
  
  header('Content-Type: application/json');
  die(json_encode(true));
}

?>