<?php

class Config {

	public static $SITE_NAME = 'Website Name';
	public static $FILTER_RULES = 'Filter Rules';
	public static $CONTENT_LOCATION = 'Content Location';
	public static $CONTENT_OUTPUT = 'Content Output';
	
	private static $defaultValues = NULL;
	
	private $configValues;
	
	public function __construct($values){
		// Set up the ocnfiguration with default values
		$this->configValues = self::getDefaultValues();
		
		// Override any default value if it was specified in the passed values
		foreach ($values as $key => $value){
			$this->configValues[$key] = $value;
		}
	}
	
	public function get($key){
		return $this->configValues[$key];
	}
	
	public function getValues(){
		return $this->configValues;
	}
	
	public static function getDefaultValues(){
		if (self::$defaultValues == NULL){
			self::$defaultValues = array(
				self::$SITE_NAME => 'Default Website Name',
				self::$FILTER_RULES => array('.*' => array('Filter1', 'Filter2')),
				self::$CONTENT_LOCATION => 'content',
				self::$CONTENT_OUTPUT => 'build',
			);
		}
		
		return self::$defaultValues;
	}


	public static function generateConfiguration(){
		$defaultValues = self::getDefaultValues();
		
		$siteName = self::$SITE_NAME;
		$filterRules = self::$FILTER_RULES;
		$contentLocation = self::$CONTENT_LOCATION;
	
		$file = fopen('config.template.json', 'w') or die('Could not write to the file config.template.json!');
		
		fwrite($file, <<<TEMPLATE
{
	/* @description
       This is the name of the website, and will generally appear as
	   the title of each page. 
	*/
	"{$siteName}": "{$defaultValues[$siteName]}",
	
	/* @description
	   This is the location of the content for the website.
	*/
	"{$contentLocation}": "{$defaultValues[$contentLocation]}",
	
	/* @description
	   This is the set of rules on how to apply filters. It is an
	   array, for the key of each item is the Regular Expression 
	   pattern used to find matching files, and the value is the 
	   array of filters to apply to all files matching this pattern.
	   Note that the filters are applied in order.
	*/
	"{$filterRules}": {
		".*": ["Markdown"]
	}
}
TEMPLATE
		);
		
		fclose($file);
	}
}