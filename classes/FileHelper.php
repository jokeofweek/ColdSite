<?php

class FileHelper {

	public static function copyStructure($dirOriginal, $dirDest){
		// This assumes an empty directory.
		$handle = opendir($dirOriginal);
		
		while ($dir = readdir($handle)){
			if ($dir != '.' && $dir != '..'){
				if (is_dir($dirOriginal.'/'.$dir)){
					mkdir($dirDest.'/'.$dir);
					self::copyStructure($dirOriginal.'/'.$dir, $dirDest.'/'.$dir);
				}
			}
		}
		
		closedir($handle);
	}

	public static function getFiles($path, &$files ){
	
		$handle = opendir($path);
		
		$file = '';
	
		while ($file = readdir($handle)){
			if ($file != '.' && $file != '..'){
				if (is_dir($path.'/'.$file)){
					self::getFiles($path.'/'.$file, $files);
				} else {
					array_push($files, new File($path.'/'.$file, array(), file_get_contents($path.'/'.$file)));
				}
			}
		}
	
		
		closedir($handle);
	}

	public static function emptyDirectory($path){
		$handle = opendir($path);
		
		while ($file = readdir($handle)){
			if ($file != '.' && $file != '..'){
				if (is_dir($path.'/'.$file)){
					self::emptyDirectory($path.'/'.$file);
					rmdir($path.'/'.$file) or die('The build folder could not be deleted. The site was not generated.');
				} else {
					unlink($path.'/'.$file) or die('Files in the folder could not be deleted. The site was not generated.');;
				}
			}
		}
		
		closedir($handle);
	}

}