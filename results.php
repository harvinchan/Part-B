<!DOCTYPE HTML PUBLIC
"-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Result</title>
</head>
<body>
<style>
h2
{
font-family:Arial,Helvetica,sans-serif;
text-align:center;
margin-left:auto;
}
table
{
font-family:Arial,Helvetica,sans-serif;
border-collapse:collapse;
margin-left:auto;
margin-right:auto;
width:70%;
}
table,th, td
{
text-align:center;
padding:5px;
vertical-align:middle;
border: 1px solid black;
}

</style>
<?php

  function showerror() {
     die("Error " . mysql_errno() . " : " . mysql_error());
  }

  require 'db.php';

  // Validation
  function checkNumber($number, $input)
        {
           if(!is_numeric($number) && $number != "")
           {
              echo 'Error - '.$input.' must be numeric.' ?> <br/> <?php ;
              return FALSE;
           }
           else
              return TRUE;
        }

  // Show all wines in a region in a <table>
  function displayWinesList($connection, $query, $regionName) {
    // Run the query on the server
    if (!($result = @ mysql_query ($query, $connection))) {
      showerror();
    }

    // Find out how many rows are available
    $rowsFound = @ mysql_num_rows($result);

    // If the query has results ...
    if ($rowsFound > 0) {
      // ... print out a header
print "<h2>Search Result(s)</h2><br>";

      	// and start a <table>.
      	print "\n<table><tr>" .
          	"\n\t<th>Wine Name</th>" .
		"\n\t<th>Grape Variety</th>" .
          	"\n\t<th>Year</th>" .
          	"\n\t<th>Winery</th>" .
		"\n\t<th>Region</th>" .
		"\n\t<th>Cost in Inventory</th>" .
		"\n\t<th>Total Available Bottles</th>" .
		"\n\t<th>Total Stock Sold</th>" .
          	"\n\t<th>Total Sales Revenue</th>\n</tr>";

      // Fetch each of the query rows
      while ($row = @ mysql_fetch_array($result)) {
        // Print one row of results
        print 	"\n<tr>\n\t<td>{$row["wine_name"]}</td>" .
            	"\n\t<td>{$row["variety"]}</td>" .
            	"\n\t<td>{$row["year"]}</td>" .
            	"\n\t<td>{$row["winery_name"]}</td>" .
		"\n\t<td>{$row["region_name"]}</td>" .
		"\n\t<td>{$row["cost"]}</td>" .
		"\n\t<td>{$row["on_hand"]}</td>" .
		"\n\t<td>{$row["TotalStockSold"]}</td>" .
            	"\n\t<td>{$row["TotalRevenue"]}</td>\n</tr>";
      } // end while loop body

      // Finish the <table>
      print "\n</table>";
    } // end if $rowsFound body
	else
	{
		print "No records found matching your criteria<br/>";
	}
    // Report how many rows were found
    print "{$rowsFound} records found matching your criteria<br>";
  } // end of function

  // Connect to the MySQL server
  if (!($connection = @ mysql_connect(DB_HOST, DB_USER, DB_PW))) {
    die("Could not connect");
  }

  // get the user data
  $wineName = $_GET['wineName'];
  $wineryName = $_GET['wineryName'];
  $regionName = $_GET['regionName'];
  $grapeVariety = $_GET['grapeVariety'];
  $lowYear = $_GET['lowYear'];
  $upYear = $_GET['upYear'];
  $minStock = $_GET['minStock'];
  $minOrdered = $_GET['minOrdered'];
  $minCost = $_GET['minCost'];
  $maxCost = $_GET['maxCost'];  

  if (!mysql_select_db(DB_NAME, $connection)) {
    showerror();
  }

  // Start a query ...
  $query = "SELECT wine_name, variety, year, winery_name, region_name, cost, on_hand, SUM(items.qty) AS TotalStockSold, SUM(items.price) AS TotalRevenue 
FROM winery, region, wine, items, inventory, grape_variety, wine_variety
WHERE winery.region_id = region.region_id 
AND wine.winery_id = winery.winery_id 
AND wine_variety.wine_id = wine.wine_id 
AND wine_variety.variety_id = grape_variety.variety_id 
AND inventory.wine_id = wine.wine_id 
AND items.wine_id = wine.wine_id";

  // ... then, if the user has specified a region, add the regionName
  // as an AND clause ...
  if (isset($regionName) && $regionName != "All") {
    $query .= " AND region_name = '{$regionName}'";
  }

  if (isset($wineName) && $wineName != "" ) {
    $query .= " AND wine_name LIKE '%{$wineName}%'";	
  }

  if (isset($wineryName) && $wineryName != "") {
    $query .= " AND winery_name LIKE '%{$wineryName}%'";
  }

  if (isset($grapeVariety) && $grapeVariety != "All") {
    $query .= " AND grape_variety.variety = '{$grapeVariety}'";
  }

  if (isset($lowYear) && isset($upYear) && $lowYear != "All" && $upYear != "All"){
    $query .= " AND year BETWEEN '{$lowYear}' AND '{$upYear}'";
  }

  if (isset($minStock) && $minStock != "") {
    $query .= " AND inventory.on_hand >= '{$minStock}'";
  }

  if (isset($minCost) && isset($maxCost) && $minCost != "" && $maxCost != "") {
    $query .= " AND cost >= '{$minCost}' AND cost <= '{$maxCost}'";
  }

  // ... and then complete the query.
  $query .= " GROUP BY wine.wine_id, variety ";

  if (isset($minOrdered) && $minOrdered != "") {
    $query .= " HAVING TotalStockSold  >= '{$minOrdered}'";
  }

  $query .= " ORDER BY wine_name;";
  
  // run the query and show the results
  if(!checkNumber($minCost,'Minimum Cost'))
      echo '<a href="search.php">GO BACK</a>';
  else if(!checkNumber($maxCost,'Maximum Cost'))
  {
     echo '<a href="search.php">GO BACK</a>'; 
  }
   else if(!checkNumber($minStock,'Minimum Stock'))
  {
     echo '<a href="search.php">GO BACK</a>';
  }
   else if(!checkNumber($minOrdered, 'Minimum Ordered'))
  {
     echo '<a href="search.php">GO BACK</a>';
  }
   else if($lowYear > $upYear)
  {
     echo 'Error - Minimum Year must be lower than Maximum Year'; ?> <br/> <?php
     echo '<a href="search.php">GO BACK</a>';
  }
  else if($minCost > $maxCost)
  {
     echo 'Error - Minimum Cost must be lower than Maximum Cost'; ?> <br/> <?php
     echo '<a href="search.php">GO BACK</a>';
  }



  else
     displayWinesList($connection, $query, $regionName);
?>
</body>
</html>

