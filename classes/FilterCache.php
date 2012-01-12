<?php

class FilterCache {
	private $filters;
	
	public function __construct(){
		$this->filters = array();
	}
	
	public function loadFilter($name){
		$className = 'Filter_'.$name;
		
		$filter = new $className;
		
		$this->filters[$name] = $filter;
	}
	
	public function get($name){
		if (!array_key_exists($name, $this->filters)){
			$this->loadFilter($name);
		}
		return $this->filters[$name];
	}
	
	public function getFilters(){
		return $this->filters;
	}
}