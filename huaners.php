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

	$shortOps = 'c';
	
	$longOpts = array();
	// Options for AWS
	$awsOpts = array("accesskey:","secretkey:","bucket:");
	// Options for SQL
	$sqlOpts = array("sqluser:","sqlpass:","sqlhost:","sqldb:","sqltype:","sqlport:");
	// Options for SCP
	$scpOpts = array("scpuser:","scphost:","scpidfile:","scpport:");
	// Options for FTP
	$ftpOpts = array("ftpuser:","ftppass:","ftphost:","ftpport:");
	// Options for Email
	$emailOpts = array("emailto:","smtpuser:","smtppass:","smtphost:","smtpport:");
	// General Options
	$generalOpts = array("help","wordpress");
	// Put into a single array.
	$longOpts = array_merge($longOpts,$awsOpts,$sqlOpts,$scpOpts,$ftpOpts,$generalOpts);
	
	
	$options  = getopt($shortOps, $longOpts);
	
	if(array_key_exists('help', $options) || $GLOBALS['argc'] == 1)
    {
        echo "Usage: " . $GLOBALS['argv'][0] . PHP_EOL;
        echo "-c : Runs using the settings in the configuration file. Recommended for crontasks.".PHP_EOL;
        echo PHP_EOL;
        echo "aws options".PHP_EOL;
        echo "-----------".PHP_EOL;
        echo "--accesskey <access key> : The access key for AWS".PHP_EOL;
        echo "--secretkey <secret key> : The secret key for AWS".PHP_EOL;
        echo "--bucket <bucket name>   : The name of the bucket that will be used".PHP_EOL;
        echo PHP_EOL;
        echo "sql options".PHP_EOL;
        echo "-----------".PHP_EOL;
        echo "--sqluser <username> : Username for SQL access".PHP_EOL;
        echo "--sqlpass <password> : Password for SQL access".PHP_EOL;
        echo "--sqlhost <hostname> : Hostname/IP that the SQL server is using".PHP_EOL;
        echo "--sqlport <port>     : The port number that the SQL server is bound to.".PHP_EOL;
        echo "--sqldb <dbname>     : The name of the database to backup.".PHP_EOL; 
        echo PHP_EOL;
        echo "FTP options".PHP_EOL;
        echo "--ftpuser <username> : Username for FTP access.".PHP_EOL;
        echo "--ftppass <password> : Password for FTP access.".PHP_EOL;
        echo "--ftphost <hostname> : Hostname/IP that the FTP is on.".PHP_EOL;
        echo "--ftpport <port>     : Port number that the FTP server is bound to.".PHP_EOL;
        echo PHP_EOL;
        echo "SCP options".PHP_EOL;
        echo "--scpuser <username> : Username for SCP access.".PHP_EOL;
        echo "--scphost <hostname> : Hostname/IP for SCP access.".PHP_EOL;
        echo "--scpidfile <file>   : Location of the file to be used as an identy file by scp.".PHP_EOL;
        echo "--scppport <port>    : The port number SCP is bound to".PHP_EOL;
        echo PHP_EOL;
        echo "Email Options".PHP_EOL;
        echo "--emailto <email>     : The email address the backups/reports are to be sent".PHP_EOL;
        echo "--smtpuser <username> : The username for SMTP auth".PHP_EOL;
        echo "--smtppass <password> : The password for SMTP auth".PHP_EOL;
        echo "--smtphost <hostname> : The hostname/IP for the SMTP server".PHP_EOL;
        echo "--smtpport <port>     : The port number the SMTP server is bound too".PHP_EOL;
        
        exit;
    }
    
    if ( !is_readable(ROOT_DIR."/etc/config.php") ){
    	echo "Fatal Error".PHP_EOL;
    	echo "Unable to read configuration file".PHP_EOL;
    	echo "Please check your permissions on ".ROOT_DIR."/etc/config.php".PHP_EOL;
    	exit;
    }
	
    require_once ROOT_DIR.'/etc/config.php';
    require_once ROOT_DIR.'/lib/Huaner/Core.php';
    
    if ( $generalSettings['sample'] === true ){
    	echo "Fatal Error".PHP_EOL;
    	echo "Configuration is sample configuration.".PHP_EOL;
    	echo "Please edit the configuration file".PHP_EOL;
    	exit;
    } 
    
    // prepare configuration.
    // TODO neaten up
    $config = array();
    $config['general'] = $generalSettings;
    $config['aws'] = $awsSettings;
    $config['ftp'] = $ftpSettings;
    $config['email'] = $mailSettings;
    $config['sql'] = $sqlSettings;
    $config['scp'] = $scpSettings;
    
    HuanersCore::setConfig($config);
  //  HuanersCore::handleOptions($options);