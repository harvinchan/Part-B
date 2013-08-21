<!DOCTYPE HTML PUBLIC
"-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
</head>
<body>
	<h2>Winestore Database</h2>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?> " method = "post">
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

    	// Retrieve each row from the query
    	while ($row = @ mysql_fetch_array($resultId))
    	{
     	// Get the value for the attribute to be displayed
     	$result = $row[$attributeName];

     	// Check if a defaultValue is set and, if so, is it the
     	// current database value?
     	if (isset($defaultvalue) && $result == $defaultValue)
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
	?>
		Wine Name:<input name="winename" type="text"><br />
		Winery Name:<input name="wineryname" type="text"><br />
		Region: <?php selectDistinct($connection, "region", "region_name", "region", "All"); ?><br />
		Grape Variety: <?php selectDistinct($connection, "wine", "wine_type", "wineType", "All"); ?><br />
		Year: <?php selectDistinct($connection, "wine", "year", "lowYear", "All"); ?> &nbsp-&nbsp<?php selectDistinct($connection, "wine", "year", "upYear", "All"); ?> 
		<br />Minimum number of wines In Stock:<input name="minstock" type="text"><br />
		Minimum number of wines Ordered:<input name="minordered" type="text"><br />
		Cost Range:<input name="mincost" type="text">&nbsp&nbsp<input name="maxcost" type="text">
		<br /><input name="submitBtn" type = "submit">	
	</form>
</body>
</html>
