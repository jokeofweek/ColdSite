<?php

class File{

	private $filename;
	private $metadata;
	private $content;

	public function __construct($filename, $metadata, $content){
		$this->setFilename($filename);
		$this->setMetadata($metadata);
		$this->setContent($content);
	}
	
	public function getFilename(){
		return $this->filename;
	}
	
	public function setFilename($filename){
		$this->filename = $filename;
	}

	public function getMetadata(){
		return $this->metadata;
	}

	public function setMetadata($metadata){
		$this->metadata = $metadata;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content = $content;
	}

}