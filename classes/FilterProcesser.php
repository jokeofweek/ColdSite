<?php

class FilterProcesser {
	private $config;
	private $filterCache;
	
	public function __construct($config){
		$this->config = $config;
		$this->filterCache = new FilterCache();
	}
	
	public function filter($file){
		// Search through the filter rules, applying the first rule
		// which matches the file name.
		$filterRules = $this->config->get(Config::$FILTER_RULES);
		
		$fileName = str_replace($this->config->get(Config::$CONTENT_LOCATION).'/', '', $file->getFilename());
		
		foreach ($filterRules as $rule => $value){
			if (preg_match('/'.$rule.'/', $fileName)){
				foreach ($value as $filter){
					$this->filterCache->get($filter)->apply($file);
				}
				break;
			}
		}
		
		// Save the content
		//file_put_contents($this->config->get(Config::$CONTENT_OUTPUT).'/'.$fileName, $content);
	}
}