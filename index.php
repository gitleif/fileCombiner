<?php
/**
* Simple demo : How to use filecombiner class
*
*  Author : Leif Nesheim (gitleif)
*  Url: https://github.com/gitleif/filecombiner
*/
// simple timer
$before = microtime(true);


// Add the filecombiner class
require("src/filecombiner.php");

// Instantiation of the filebombiner
$Combiner = new fileCombiner();

// Register a unique settings, this is for JS files
$Combiner->registerNew("JS", array("basepath"=>dirname(__FILE__) . "/js/", "outputpath"=>dirname(__FILE__) . "/js/", "extention"=>"js", "postfix"=>"?ver1", "url"=>dirname(__FILE__) . "/js/"));

// Add three JS files found in the JS Folder.
// No need to use the complete filepath, because basepath is set in registerNew
$Combiner->addFile("JS", array("jscript1.js", "jscript2.js","jscript3.js"));
?>

<html>
	<head>
		<!-- Normal way to load scripts
		
		<script src="js/jscript1.js"></script>
		<script src="js/jscript2.js"></script>
		<script src="js/jscript3.js"></script>
		-->
		
		<!-- filecombiner class -->
		<script src="js/<?php echo($Combiner->Combine("JS")); ?>"></script>
	</head>

	<body>
		<h1>JS Files Combined</h1>
		
	</body>
</html>

<?php
echo "Time used:  " . number_format(( microtime(true) - $before), 6) . " Seconds\n";
?>
