<head>
<title>Recipe List</title>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
  $("#searchTerm").on('input', function() {
    $("#search").submit();
  });
  $("#search").submit();
});

</script>
</head>

<?php

if(!isset($_POST['SearchBy'])) {
  $_POST['SearchBy'] = '';
  $_POST['Term'] = '';
}

if(!isset($_POST['SortBy'])) {
  $_POST['SortBy'] = 'Name';
  $_POST['Dir'] = 'ASC';
}

?>

<body>

<iframe class="menubox main" src="sidebar.php"></iframe>

<div class="sidebar menubox context">
  <form id=search target=recipeinfolist action="getrecipeinfolist.php" method="post">
    <select class="menusearch" name="SearchBy">
      <option value="Name" <?php if($_POST['SearchBy'] == 'Name')echo"selected='selected'";?>>Recipe name</option>
      <option value="Course" <?php if($_POST['SearchBy'] == 'Course')echo"selected='selected'";?>>Dish type</option>
      <option value="Instrument" <?php if($_POST['SearchBy'] == 'Instrument')echo"selected='selected'";?>>Appliance</option>
      <option value="Time" <?php if($_POST['SearchBy'] == 'Time')echo"selected='selected'";?>>Total time within...</option>
      <option value="Score" <?php if($_POST['SearchBy'] == 'Score')echo"selected='selected'";?>>Rating at least...</option>
      <option value="Ingredient" <?php if($_POST['SearchBy'] == 'Ingredient')echo"selected='selected'";?>>Includes ingredient</option>
    </select>
    
    <input id=searchTerm autofocus class="menusearch" name="Term" placeholder="Search Term"
      value="<?php if(isset($_POST['Term']))echo $_POST['Term'];else echo 'Search Term';?>"
      onfocus="var val = this.value; this.value = ''; this.value = val;">
    
    <?php if(isset($_POST['SortBy']))echo "<input type='hidden' name='SortBy' value='".$_POST['SortBy']."'>";?>
    <?php if(isset($_POST['Dir']))echo "<input type='hidden' name='Dir' value='".$_POST['Dir']."'>";?>
    <?php if(isset($_POST['Delete']))echo "<input type='hidden' name='Delete' value='".$_POST['Delete']."'>";?>
  </form>
</div>

<iframe name=recipeinfolist class="menubox main fullwidthcontent"></iframe>

</body>
