#!/usr/bin/php
<?php
	/**
	 * Script to back up MySQL and directories to various
	 * places using various methods such as FTP, SCP, 
	 * E-Mail and AWS.
	 *  
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bsd-license/ BSD License
	 * @package Huaners
	 * @copyright Iain Cambridge All rights reserved 2010 (c)
	 * @version 1.0
	 */
	 
	define('ROOT_DIR', dirname(__FILE__));
	// SQL
	define('SQL_USERNAME', '');
	define('SQL_PASSWORD', '');
	define('SQL_HOSTNAME', '');
	define('SQL_DATABASE', '');
	define('SQL_BACKUP', true);
	// AWS
	define('AWS_ACCESS', '');
	define('AWS_SECRET', '');
	define('AWS_BUCKET', '');
	define('AWS_BACKUP', true);
	// FTP
	define('FTP_USERNAME', '');
	define('FTP_PASSWORD', '');
	define('FTP_HOSTNAME', '');
	define('FTP_PORT', '');
	define('FTP_BACKUP', false);
	// SMTP
	define('SMTP_USERNAME', '');
	define('SMTP_PASSWORD', '');
	define('SMTP_HOSTNAME', '');
	define('SMTP_PORT', '');
	define('EMAIL_REPORT', true);
	define('ATTACH_BACKUP', true);
	
	// Set stuff that need set :~)
	date_default_timezone_set('America/Los_Angeles');
	error_reporting(-1);
	ini_set("display_errors",1);
	
	$shortOpts = "wtqd:";
	$longOpts = array("help","wordpress","dir:");
	
	$options  = getopt($shortOpts, $longOpts);
	
	if(array_key_exists('help', $options) || $GLOBALS['argc'] == 1){
		print "Huaners version 1.0".PHP_EOL;
		print "===================".PHP_EOL;
		print " -w (--wordpress) - Does wordpress backup.".PHP_EOL;
		print " -d (--dir) <dir> - The directory that is to be backed up.".PHP_EOL;
		print " -t (--transfer)  - Does the transfer of files according to settings in file.".PHP_EOL;
		print " -q (--quiet)     - Doesn't print out any messages.".PHP_EOL;
		exit;
	}
	
	if ( !array_key_exists("dir",$options) && !array_key_exists("d",$options) ){
		print "Directory must be defined".PHP_EOL;
	} else {
		$dir = ( array_key_exists("dir", $options) ) ? $options["dir"] : $options["d"];
		define("BACKUP_DIR",ROOT_DIR."/".$dir);
	}
	
	if ( (array_key_exists("transfer", $options) || array_key_exists("t",$options) ) ){
		
		define("TRANSFER",true);
		// Check dependicies
		if ( AWS_BACKUP === true ){
			if ( !is_readable(ROOT_DIR."/lib/awssdk/sdk.class.php") ){
				print "AWS PHP SDK not found, please download and put in ".ROOT_DIR."/lib/awssdk/sdk.class.php".PHP_EOL;
				exit;
			} 			
			require_once ROOT_DIR."/lib/awssdk/sdk.class.php";
		}
		
		if ( FTP_BACKUP === true ){
			if ( !extenstion_loaded("ftp") ) {
				print "PHP Doesn't have ftp enabled".PHP_EOL;
				exit;
			}
		}
		
	} else {
		define("TRANSFER",false);
	}
	
	if ( array_key_exists("wordpress", $options) || array_key_exists("w",$options) ) {		
		define("WORDPRESS",true);			
	} else {
		define("WORDPRESS",false);
	}
	
	if ( array_key_exists("quiet", $options) || array_key_exists("q",$options) ){
		define("QUIET", true);
	} else {
		define("QUIET", false);
	}
	// Move this to function if/when I add other cmses
	$backupMode = ( WORDPRESS === true ) ? "WordPress" : "Normal"; 
	
	define("TMP_DIR", ROOT_DIR."/huaners-tmp");
	if ( file_exists(TMP_DIR) ){
		exec("rm -Rf ".TMP_DIR);
	}
	mkdir(TMP_DIR);
	
	qprint("Huaners 1.0");
	qprint("================");
	qprint("Archiving : ".BACKUP_DIR);
	qprint("Mode : ".$backupMode);
	qprint("Starting...");		
	
	exec('cp -R '.BACKUP_DIR.' '.TMP_DIR,$output,$result);
	
	if ( WORDPRESS ){
		getWpConfig();
	}
	getDb();
	$archiveFile = archiveDir();
	
	if ( TRANSFER ){
		uploadAws($archiveFile);
	}
	
	qprint("Finished");
	
	exec("rm -Rf ".TMP_DIR);
	
	/**
	 * Handles the printing of non error messages,
	 * got the idea from Tyler Hall's autosmush.
	 * 
	 * @since 1.0
	 */
	function qprint($string){
		if (!QUIET){
			print $string.PHP_EOL;
		}	
	}
	
	/**
	 * Handles the archiving of the directory using
	 * tar.
     * 
	 * @return string the location of the archive. 
	 */
	function archiveDir(){
		
		qprint("Starting archive creation");
		$archiveFile = date("m-d-y")."-backup.tar.gz";
		
		exec("tar -C ".ROOT_DIR." -zcf ".$archiveFile." ".TMP_DIR
			,$output,$status);
			
		if ( !file_exists(ROOT_DIR."/".$archiveFile) ){			
			qprint("Unable to create archive file",true);
			exit;
		}

		qprint("Archive created");
		
		return ROOT_DIR."/".$archiveFile;
		
	}
	
	/**
	 * Upload the backup file to 
	 * @param unknown_type $archiveFile
	 */
	function uploadAws($archiveFile){

		if ( !AWS_BACKUP ){
			return;
		}
		
		qprint("Starting S3 Upload");
		
		$s3 = new AmazonS3(AWS_ACCESS,AWS_SECRET);
		if ( !$s3->if_bucket_exists(AWS_BUCKET) ){
			qprint("Amazon S3 bucket doesn't exist",true);
			exit;
		}
		
		$options = array(
                     "fileUpload" => $archiveFile,
                     "acl" => AmazonS3::ACL_PRIVATE, // Don't want people walking away with the dump
                     "contentType" => "application/x-gzip",
                   );
		$s3->create_object(AWS_BUCKET, basename($archiveFile), $options);		
		
		qprint("Archive Uploaded to S3");
	}
	
	/**
	 * Fetches the SQL details for WordPress
	 * from wp-config.php.
	 * 
     * @since 1.0
	 */
	function getWpConfig(){
		
		if ( !is_readable(BACKUP_DIR."/wp-config.php") ){
			qprint("WordPress config file not found",true);
		}
		
		$wpConfig = file_get_contents(BACKUP_DIR."/wp-config.php");
		
		preg_match("~define\('DB_NAME', '(.*)'\);~isU",$wpConfig,$wpDatabase);
		preg_match("~define\('DB_USER', '(.*)'\);~isU",$wpConfig,$wpUsername);
		preg_match("~define\('DB_PASSWORD', '(.*)'\);~isU",$wpConfig,$wpPassword);
		preg_match("~define\('DB_HOST', '(.*)'\);~isU",$wpConfig,$wpHostname);
		
		if ( is_array($wpDatabase) && array_key_exists(1, $wpDatabase ) ){
			define("WP_DB_NAME",$wpDatabase[1]);
		} else {
			var_dump($wpDatabase);
			qprint("Unable to find database name from config",true);
			exit;
		}
		
		if ( is_array($wpUsername) && array_key_exists(1, $wpUsername) ){
			define("WP_DB_USER",$wpUsername[1]);
		} else {
			qprint("Unable to find database user from config",true);
			exit;
		}	
		
		if ( is_array($wpPassword) && array_key_exists(1, $wpPassword) ){
			define("WP_DB_PASS",$wpPassword[1]);
		} else {
			qprint("Unable to find database password from config",true);
			exit;
		}
		
		if ( is_array($wpHostname) && array_key_exists(1, $wpHostname) ){
			define("WP_DB_HOST",$wpHostname[1]);
		} else {
			qprint("Unable to find database hostname from config",true);
			exit;
		}
		
	}
	
	/**
	 * Gets the database dump
	 * 
	 * @since 1.0
	 */
	function getDb(){
		
		if ( !SQL_BACKUP && !WORDPRESS ){
			return false;
		}
		
		if ( WORDPRESS ){
			exec("mysqldump --host='".WP_DB_HOST."' --user='".WP_DB_USER."' --password='".WP_DB_PASS."' --database ".WP_DB_NAME." >> ".TMP_DIR."/".WP_DB_NAME."-backup.sql");			
		} else {
			exec("mysqldump --host='".SQL_HOSTNAME."' --user='".SQL_USERNAME."' --password='".SQL_PASSWORD."' --database ".SQL_DATABASE." >> ".TMP_DIR."/".SQL_DATABASE."-backup.sql");	
		}
	
		qprint("Dumped database");
		
		return true;
	}