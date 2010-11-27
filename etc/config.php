<?php
	/**
	 * Sample configuration shows the multiple ways to create your configuration file.
	 * 
	 * 
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bsd-license/ BSD License
	 * @package Huaners
	 * @copyright Iain Cambridge All rights reserved 2010 (c)
	 * @version 1.0
	 */

// Single declare statement array within.
$awsSettings = array(
				 array('access_key' => '',
				 	   'secret_key' => '',
				 	   'bucket_name' => '')
				   );

$stpSettings = array();
$stpSettings[] = array('username' => '', 'password' => '', 'hostname' => '', 'port' => 21 );				   