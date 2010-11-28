<?php

class HuanerWordpress extends HuanersCore {
	
	public function __construct(){
	
		$general = HuanerCore::getConfig('general');
		
		if ( !is_array($general['dir']) ){
			$this->dirs = array($general['dir']);
		} else {
			$this->dirs = $general['dir'];
		}
		
		
		
		
	}
	
	protected function _getConfig(){
	
		foreach ( $this->dirs as $dir ) {
			
			if ( file_exists($dir) ) {
				if ( !is_readable($dir.'/wp-config.php') ){
					
				}
			}
			
		}
		
	
	}
	
	
}