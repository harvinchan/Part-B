<html>
<head>
</head>
<body>
	<h2>Winestore Database</h2>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?> " method = "post">
		Wine Name:<input name="winename" type="text"><br />
		Winery Name:<input name="wineryname" type="text"><br />
		Region:<input name="winename" type="text"><br />
		Grape Variety:<input name="winename" type="text"><br />
		Years:<input name="lowyear" type="text">&nbsp&nbsp<input name="upyear" type="text">
		<br />Minimum number of wines In Stock:<input name="minstock" type="text"><br />
		Minimum number of wines Ordered:<input name="minordered" type="text"><br />
	Cost Range:<input name="mincost" type="text">&nbsp&nbsp<input name="maxcost" type="text">
	<br /><input name="submitBtn" type = "submit">	
	</form>
</body>
</html>
