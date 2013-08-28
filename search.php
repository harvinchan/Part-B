<!DOCTYPE HTML PUBLIC
"-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
</head>
<body>
	<h2>Winestore Database</h2>
	<form action="results.php" method = "GET">
	<?php
  	require "db.php";
	
	//Show Error Function
	function showerror() {
     	die("Error " . mysql_errno() . " : " . mysql_error());
  	}

	// selectDistinct() function
  	function selectDistinct ($connection, $tableName, $attributeName, $pulldownName, $defaultValue) {
    	$defaultWithinResultSet = FALSE;

    	// Query to find distinct values of $attributeName in $tableName
    	$distinctQuery = "SELECT DISTINCT {$attributeName} FROM
	{$tableName}";

    	// Run the distinctQuery on the databaseName
    	if (!($resultId = @ mysql_query ($distinctQuery, $connection)))
      	showerror();

    	// Start the select widget
    	print "\n<select name=\"{$pulldownName}\">";
		
        if ($tableName !='region')
		print "\n\t<option value = \"All\">All</option>";
    	
	// Retrieve each row from the query
    	while ($row = @ mysql_fetch_array($resultId))
    	{
     	// Get the value for the attribute to be displayed
     	$result = $row[$attributeName];

     	// Check if a defaultValue is set and, if so, is it the
     	// current database value?
     	if (isset($defaultValue) && $result == $defaultValue)
       	// Yes, show as selected
       	print "\n\t<option selected value=\"{$result}\">{$result}";
     	else
       	// No, just show as an option
       	print "\n\t<option value=\"{$result}\">{$result}";
     	print "</option>";
    	}
    	print "\n</select>";
  	} // end of function

  	// Connect to the server
  	if (!($connection = @ mysql_connect(DB_HOST, DB_USER, DB_PW))) {
    		showerror();
  	}

  	if (!mysql_select_db(DB_NAME, $connection)) {
    		showerror();
  	}		
	// Search Form ?>
		Wine Name:<input name="wineName" type="text"><br />
		Winery Name:<input name="wineryName" type="text"><br />
		Region: <?php selectDistinct($connection, "region", "region_name", "regionName", ""); ?><br />
		Grape Variety: <?php selectDistinct($connection, "grape_variety", "variety", "grapeVariety", "All"); ?><br />
		Year: <?php selectDistinct($connection, "wine", "year", "lowYear", "All"); ?> &nbsp-&nbsp<?php selectDistinct($connection, "wine", "year", "upYear", "All"); ?> 
		<br />Minimum number of wines In Stock:<input name="minStock" type="text"><br />
		Minimum number of wines Ordered:<input name="minOrdered" type="text"><br />
		Cost Range:<input name="minCost" type="text">&nbsp&nbsp<input name="maxCost" type="text">
		<br /><input name="submitBtn" type = "submit">	
	</form>
</body>
</html>
