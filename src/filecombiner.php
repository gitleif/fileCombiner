<?php
/**
* fileCombiner
*
*  All rights reserved. The fileCombiner is a PHP Class to
*  combine e.g javascript/css files. Combined files
*  will make your website lighter and faster.
*
*  Author : Leif Nesheim (gitleif)
*  Url: https://github.com/gitleif/filecombiner
*  Created: 2019-08-26
*  Version: 1.0.0
*
* -----------------------------------------------------------------------------
*/
class fileCombiner
{
	private $_Settings = null;

	// Nothing fancy here
	public function __construct(){}

	// Register new objekt for each filetype, e.g JS/CSS
	// Example: registerNew("CSS", $config);
	// Example: registerNew("JS", $config);
	// Example: registerNew("CSS2", $config);
	public function registerNew($Key, $configarr)
	{
		$this->_Settings[$Key]['postfix'] = isset($configarr['postfix']) ? $configarr['postfix'] : null;
		$this->_Settings[$Key]['prefix'] = isset($configarr['prefix']) ? $configarr['prefix'] : null;
		$this->_Settings[$Key]['version'] = isset($configarr['version']) ? $configarr['version'] : null;
		$this->_Settings[$Key]['extention'] = isset($configarr['extention']) ? "." . $configarr['extention'] : null;
		$this->_Settings[$Key]['outputpath'] = isset($configarr['outputpath']) ? $configarr['outputpath'] : null;
		$this->_Settings[$Key]['basepath'] = isset($configarr['basepath']) ? $configarr['basepath'] : null;
		$this->_Settings[$Key]['files'] = null;
	}

	// Function to use when combining the added files.
	// cbProcess is a callback funtion, used to run custom minify functions.
	// cbProcess(Filename, Content){return Content};
	// return: filename of the new generated file
	public function Combine($Key, $cbProcess = null)
	{
		// Sort temporary array
		$tmpFiles = $this->_Settings[$Key]['files'];
		sort($tmpFiles);
		// Array into string
		$BaseName = $this->_Settings[$Key]['prefix'] . md5(implode("#", $tmpFiles   ) . $this->_Settings[$Key]['version'] ) . $this->_Settings[$Key]['extention'];
		$tmpFileName = $this->_Settings[$Key]['outputpath'] . $BaseName;

		// check if tempfilename exist
		if(file_exists($tmpFileName))
		{
			return($BaseName . $this->_Settings[$Key]['postfix']);

		}
		else {
			// Merge these files
			file_put_contents($tmpFileName, $this->buildCombinedFile($Key, $this->_Settings[$Key]['files'], $cbProcess));
			return($BaseName . $this->_Settings[$Key]['postfix']);
		}
	}

	// internal function to merge the content og added files.
	private function buildCombinedFile($Key, $FileNames, $cbProcess = null)
	{
			// Loop through each file and add to $array
			$_Content = null;
			foreach($FileNames as $File)
			{
				if(is_callable($cbProcess))
				{
					$_Content[] = $cbProcess($this->_getFileRealPath($Key, $File), file_get_contents($this->_getFileRealPath($Key, $File)));
				}
				else {
					$_Content[] = file_get_contents($this->_getFileRealPath($Key, $File));
				}
			}

			// Slå sammen disse
			$tmpContent = implode("\n",$_Content);

			// Gjer anna humbug her
			return($tmpContent);
	}

	// Use this function to add file you will add to the combined file
	public function addFile($Key, $FileName)
	{
		if(is_array($FileName))
		{
			foreach($FileName as $File)
			{
				$this->_addSingleFile($Key, $File);
			}
		}
		else {
			$this->_addSingleFile($Key, $FileName);
		}
	}

	// internal function
	private function _addSingleFile($Key, $FileName)
	{
		if(!isset($this->_Settings[$Key]['files'][$FileName]) && file_exists($this->_getFileRealPath($Key, $FileName)))
		{
			$this->_Settings[$Key]['files'][] = $FileName;
			return true;
		}
		return false;
	}

	// internal function
	private function _getFileRealPath($Key, $FileName)
	{
			if($this->_Settings[$Key]['basepath']!=null)
			{
				$FileName = $this->_Settings[$Key]['basepath'] . $FileName;
			}
			return $FileName;
	}

}

 ?>
