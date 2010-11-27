<?php

	/**
	 * Core methods of the huaners system. Such
	 * as factory methods.
	 * 
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bsd-license/ BSD License
	 * @package Huaners
	 * @copyright Iain Cambridge All rights reserved 2010 (c)
	 * @version 1.0
	 */

class HuanersCore {
	
	/**
	 * Contains the configuration for the runtime.
	 * @var array
	 */
	
	private static $config;
	
	
	/**
	 * Gets the backup type object, which does 
	 * the heavy lifting.
	 * 
	 * @param string $objectType
	 * @return HuanersCore
	 * @since 1.0
	 */
	
	public static function getBackupObject($objectType){
		
		switch( strtolower($objectType) ){			
			case 'wordpress':
				return new HuanersWordpress();
			case 'sql':
				return new HuanersSql();
			case 'filesystem':
				return new HuanersFilesystem();
			case 'all':
			default:
				return new HuanersTotal();
		}
		
	}
	
	
	/**
	 * Factory method for upload objects.
	 * 
	 * @param string $uploadMethod
	 */
	
	public static function getUploadObject($uploadMethod){
		
		switch ( strtolower($str) ){
			case 'aws':
				return new HuanerAws();
			case 'ftp':
				return new HuanerFtp();
			case 'scp':
			default:
				return new HuSectionanerScp();
				
		}
		
	}
	
	/**
	 * Getter for the configuration.
	 * 
	 * @param string|boolean $configSection
	 */
	
	public static function getConfig($configSection = false){
		
		if ( $configSection === false ){
			return self::$config;	
		} else {			
			if ( isset(self::$config[$configSection]) ){
				return self::$config[$configSection];
			} else {
				return false;
			}
		}
		
	}
	
	/**
	 * Setter for the configuration.
	 * 
	 * @param mixed $value
	 * @param string|boolean $configSection
	 * @param string|boolean $configSubSection
	 */
	
	public static function setConfig($value, $configSection = false, $configSubSection = false){
		
		if ( $configSection === false ){			
			self::$config = $value;			
			return true;
		}
		
		if ( !isset(self::$config[$configSection]) ){
			return false;
		}		
		
		if ( $configSubSection !== false ){
			if ( !isset(self::$config[$configSection][$configSubSection]) ){
				return false;
			}			
			self::$config[$configSection][$configSubSection] = $value;
			
		} else {
			self::$config[$configSection] = $value;
		}
		
		
	}
	
	/**
	 * Handles assigning to over ride the general configuration.
	 * 
	 * @param unknown_type $options
	 */
	
	public static function handleOptions($options){
		
		if ( empty($options) ){
			// no options so do nothing!
			return true;
		}
		// AWS config
		if (  array_key_exists( 'accesskey' , $options ) 
		   && array_key_exists( 'secretcode' , $options)
		   && array_key_exists( 'bucket' , $options ) ){
		   	
		   self::$config['aws'][] = array(
		   								'access_key' => $options['accesskey'],
		   								'secret_key' => $options['secretkey'],
		   								'bucket_name' => $options['bucket']
		   								);	
		   	
		}
		
		// SQL Config
		if (  array_key_exists( 'sqluser' , $options ) 
		   && array_key_exists( 'sqlpass' , $options)
		   && array_key_exists( 'sqlhost' , $options )
		   && array_key_exists( 'sqldb' , $options )
		   && array_key_exists( 'sqltype' , $options ) ){
		   	
		   	$port = ( array_key_exists( 'sqlport' , $options ) ) ? $options['sqlport'] : 3306;
		   	
			self::$config['sql'][] = array('username' =>  $options['sqluser'],
					   'password' => $options['sqlpass'],  
					   'dbname' => $options['sqldb'], 
					   'hostname' => $options['sqlhost'],
					   'port' => $port); 
			
		}
		
		
		if (  array_key_exists( 'scpuser' , $options ) 
		   && array_key_exists( 'scphost' , $options ) ) {
			
		}
		if ( array_key_exists() ) {
			
		}
		if ( array_key_exists() ) {
			
		}
	}
	
	
	
	
}