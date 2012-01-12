<?php

class Builder {
	private $config;
	private $filterCache;
	
	public function __construct($config){
		$this->config = $config;
		$this->filterCache = new FilterCache();
	}
	
	public function buildPage($filePath){
		// Fetch the content
		$content = file_get_contents($filePath);
		
		// Search through the filter rules, applying the first rule
		// which matches the file name.
		$filterRules = $this->config->get(Config::$FILTER_RULES);
		
		$fileName = str_replace($this->config->get(Config::$CONTENT_LOCATION).'/', '', $filePath);
		
		foreach ($filterRules as $rule => $value){
			if (preg_match('/'.$rule.'/', $fileName)){
				foreach ($value as $filter){
					$content = $this->filterCache->get($filter)->run($content);
				}
				break;
			}
		}
		
		// Save the content
		file_put_contents($this->config->get(Config::$CONTENT_OUTPUT).'/'.$fileName, $content);
	}
}