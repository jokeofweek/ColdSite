<?php

// Set up the autooader
function __autoload($class){
	// First attempt to split by _, and check whether the file exists in
    // one of the sub folders. In order to do this, make all the path
	// parts lower case apart the class name.
	$exploded = explode('_', $class);
	
	for ($i = 0, $l = count($exploded) - 1; $i < $l; $i++){
		$exploded[$i] = strtolower($exploded[$i]);
	}
	
	$filename = implode('/', $exploded).'.php';
	if (file_exists($filename) && is_file($filename)){
		require_once($filename);
		
	// Else check if it is a class which exists in the classes folder.
	} else if (file_exists('./classes/'.$class.'.php')){
		require_once('./classes/'.$class.'.php');
		
	// Die
	} else {
		print 'The class \''.$class.'\' could not be found. The site could not'.
			  " be generataed.\n";
		die();
	}
}


// Set up the include path so that it is absolute
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

// Ensure we are running from the command line
if (PHP_SAPI !== 'cli'){
	print "This must be run from the command line.";
	die();
}

// Check command line arguments
foreach ($argv as $arg) {
	if ($arg == '--generate-config'){
		Config::generateConfiguration();
		print "The file config.template.json has been created containing the".
			  " default template for configuration.\n";
		die();
	}
}

// Load the configuration settings
if (!file_exists('config.json')){
	print "A config.json file is required, specifying the configuration options!\n";
	die();
}

// Load the contents of the JSON file, stripping out new lines andcomments. Then
// attempt to parse the JSON.
$content = preg_replace('/\/\* @description(.*?)\*\//', '',
				str_replace(array("\r\n", "\n", "\r"), '', 
					file_get_contents('config.json')));
				
$options = json_decode($content, true);


// Check for any JSON errors before continuing
if (json_last_error() != JSON_ERROR_NONE){
	print "The config.json contained invalid JSON. Please check your syntax.\n";
	die();
}

// Set up the Config object
$config = new Config($options);

// Set up the build directory if it does not exist, or else empty it
$outputLoc = $config->get(Config::$CONTENT_OUTPUT);
$inputLoc = $config->get(Config::$CONTENT_LOCATION);

if (!is_dir($outputLoc)) {
	mkdir($outputLoc);
} else {
	FileHelper::emptyDirectory($outputLoc);
}

// Copy the folder structure
FileHelper::copyStructure($inputLoc, $outputLoc);

// Set up the page builder
$builder = new Builder($config);

// Get the list of files
$files = array();
FileHelper::getFileNames($inputLoc, $files);

foreach ($files as $file){
	$builder->buildPage($file);
}