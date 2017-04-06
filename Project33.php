
<?php
if (isset($_GET['fileToSearch'])){
	processForm();
} else{
	display_form();
}
//display_form();
 
function doControl(){
	//filename
	$sourceFiles = "UKgames.json";
	$results = "";
	//gets the contents of the filename given.
	$contents = file_get_contents($sourceFiles);
	$decoded = json_decode($contents,false);
	json_last_error();
	//dumps the content of the .json file (makes it visible)
	var_dump($decoded);
	return $decoded;
}

//Displays the form of the HTML page, and sets each select option to the correct values.
function display_form(){
	startHTML();
	echo "
	<form action='Project3.php' method='get'>
	Select parameters to search:<br>
	";	

	echo "
	<p>
	<select name='fileToSearch'> ";
	$resultsDC = doControl();
	$enumFiles = $resultsDC->files;
	$enumStats = $resultsDC->stats;
	foreach($enumFiles as $key=>$value)
        {
               echo "<option value='$value'>$key</option>", "\n";
        } 

	echo "</select>";

	echo "<p><select name='statToSearch'> ";
	foreach($enumStats as $statkey=>$statvalue)
	{
		echo"<option value='$statvalue'>$statvalue</option>","\n";
	}
	echo "</select>";
	echo "<p>
	<select name='functWanted'> ";
		echo "<option value='high'>High</option>";
		echo "<option value='low'>Low</option>";
	echo "</select>";
	echo "
	<p>
	<input type='submit' value='Do search'>
	";
	endHTML();
}

function processform(){
	$filewanted = $_GET['fileToSearch'];
	$statwanted = $_GET['statToSearch'];
	$HOL = $_GET['functWanted'];
	$fileContents = doSearch($filewanted, $statwanted, $HOL);
	return;

}


function doSearch($file, $stat, $funct){
	$currStat = null;
	$currGame = null;
	$filecontents = file_get_contents($file);
	$gamesJSON = json_decode($filecontents);
	json_last_error();
//	echo "stat = ", $stat, "<p>";
	foreach($gamesJSON->games as $agame){
		if (isset($agame->$stat)){	
//		echo $agame->$stat, "<p>";
			if($funct == 'high'){
				if($agame->$stat > $currStat ){
					$currStat = $agame->$stat;
					$currGame =$agame;
				}
			}
			elseif($funct == 'low'){
				if($agame->$stat < $currStat ){
					$currStat = $agame->$stat;
					$currGame = $agame;
				}

			}
		}
	}	
	foreach($currGame as $key2=>$value2){
		echo $key2;
		echo ": ";
		echo $value2;
		echo "<br>";

	}
}

function startHTML() {

echo "
<html>
<head>
<title>Search records!</title>
</head>
<body>
<h1>Search records!</h1>
";

}


function endHTML() {

echo "
</body>
</html>
";

}

?>
