<?php
	/**
	 * Sample configuration way to create your
	 * configuration file.
	 * 
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bsd-license/ BSD License
	 * @package Huaners
	 * @copyright Iain Cambridge All rights reserved 2010 (c)
	 * @version 1.0
	 */

// Single declare statement array within.
$awsSettings = array(
				 array('access_key' => '', // Your AWS access key
				 	   'secret_key' => '', // Your AWS secret code
				 	   'bucket_name' => '') // The name of the bucket to be used.
				   );

$ftpSettings = array();
$ftpSettings[] = array('username' => '',  // Username for the FTP server
					   'password' => '',  // Password for the FTP server
					   'hostname' => '',  // Hostname for the FTP server
					   'port' => 21 );    // Port number for the FTP server

$scpSettings = array();
$scpSettings[] = array('username' => '', // Username for the SCP 
					   'server' => '',   // IP/Hostname of the server
 		 			   'id_file' => ''); // ID file for SCP

$mailSettings = array();
$mailSettings[] = array('username' => '', // Username for the SMTP server
						'password' => '', // Password for the SMTP server
						'hostname' => '', // Hostname for the SMTP server
						'port' => '',     // Port for the SMTP server
						'ssl' => false,   // To use SSL for smtp.
						'attach' => true,// Attach the backup to the email
						'report' => true ); // Send status report on the update.

$sqlSettings = array();
$sqlSettings[] = array('username' => '',  // Username for the SQL server
					   'password' => '',  // Password for the SQL server
					   'dbname' => '',    // Database to be dumped
					   'hostname' => '',  // Hostname of the SQL serv
					   'port' => '');     // Port of the SQL server

$generalSettings = array();
// This can ethier be an array or string.
$generalSettings['dir'] = array();
// Back up Types 
// All - Backs up everything 
// WordPress - Finds the SQL details automatically and copies the database and the wp-content folder as they are custom stuff
// SQL - Grabs only the SQL dumps.
// FSComplete - Grabs only the filesystem
// FSSingle - Grabs the file system only but puts them into seperate tarballs.
$generalSettings['type'] = '';
// Simple token replacement on this
// %%TIME%% - unixtime_stamp
// %%DATE%% - date
// %%HOSTNAME%% - the result of hostname on the computer.
// %%DIR%% - the dir that t
$generalSettings['backup-name'] = '';
// If we should upload via ftp.
$generalSettings['ftp'] = true;
// If we should upload to AWS s3
$generalSettings['s3'] = true;
// If we should upload via scp.
$generalSettings['scp'] = true;