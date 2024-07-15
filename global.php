<?php

$placeholderInameInvisible = "__NULL_INAME_INVISIBLE__";
$allRname = "__ALL__";

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

?>