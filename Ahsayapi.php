<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ahsay API Wrapper for CodeIgniter
 *
 * An API Wrapper for the Ahsay Backup Server written for CodeIgniter
 * loosely based on the PHP Wrapper written by Richard Bishop available 
 * here: http://forum.ahsay.com/viewtopic.php?t=3314
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author		Kyle Klaus (kklaus@indemnity83.com)
 * @author		Richard Bishop (ahsayapi@uchange.co.uk)
 * @copyright	Copyright (c) 2011, Kyle Klaus.
 * @license	GNU General Public License
 * @since		Version 1.0
 */
 
class Ahsayapi {
    
	// Private variables
	var $server;
	var $error;

	/**
	 * Connection defaults
	 */
	var $__defaults = array(
		'host' => 'localhost', 
		'user' => 'system', 
		'pass' => '',
		'timeout' => '30', 
		'path' => '/obs/api/',
		'protocol' => 'sslv3',
		'port' => '443',
	);   

	/**
	 * Constructor
	 */
	public function __construct($params = array()) {
		$this->server = array_merge($this->__defaults, $params);                
		log_message('debug', 'Library AhsayAPI Initialized');
	}

	/**
	 * Authenticate User
	 */
	function authUser($username, $password) {
		log_message('debug', "Authenticate user $username");        
		return $this->__query("AuthUser.do?LoginName=$username&Password=$password");
	}   

	/**
	 * List Users
	 */
	function listUsers($host = '') {    
		log_message('debug', "Getting user list");
		$result = $this->__query("ListUsers.do?Host=$host");		
		return $this->__parse($result);
	} 

	/**
	 * List User Backup Sets
	 */
	function listBackupSets($username) {    
		log_message('debug', "Getting backup sets for user '$username'");        
		$result =  $this->__query("ListBackupSets.do?LoginName=$username");   
		return $this->__parse($result);
	}  

	/**
	 * Get User
	 */
	function getUser($username) {    
		log_message('debug', "Getting information for user '$username'");        
		$result =  $this->__query("GetUser.do?LoginName=$username");   
		return $this->__parse($result);
	} 	

	/**
	 * Get User Storage Stats
	 */    
	function getUserStorageStat($username, $date) {
		log_message('debug', "Getting storage stats for user '$username'");
		$result =  $this->__query("GetUserStorageStat.do?LoginName=$username&YearMonth=$date");
		return $this->__parse($result);
	}    

	/**
	 * List Backup Jobs
	 */
	function listBackupJobs($username) {
		log_message('debug', "Getting backup jobs for user '$username'");
		$result =  $this->__query("ListBackupJobs.do?LoginName=$username");
		return $this->__parse($result);
	}

	/**
	 * Get Backup Set
	 */
	function getBackupSet($username, $setid) {
		log_message('debug', "Getting details for backup set with id '$setid' for user '$username'");
		$result =  $this->__query("GetBackupSet.do?LoginName=$username&BackupSetID=$setid");
		return $this->__parse($result);
	}    

	/**
	 * List Backup Job Status
	 */
	function listBackupJobStatus($date, $username = '') {
		log_message('debug', "Listing backup job status");
		$result =  $this->__query("ListBackupJobStatus.do?LoginName=$username&BackupDate=$date");
		return $this->__parse($result);
	} 

	/**
	 * Get Backup Job Report
	 */
	function getBackupJobReport($username, $setid, $jobid) {
		log_message('debug', "Getting backup job report");
		$result =  $this->__query("GetBackupJobReport.do?LoginName=$username&BackupSetID=$setid&BackupJobID=$jobid");
		return $this->__parse($result);
	} 	
	
	/**
	 * Get Backup Job Report Summary
	 */
	function getBackupJobReportSummary($username, $setid, $jobid, $cdp = 'N') {
		log_message('debug', "Getting backup job report summary");
		$result =  $this->__query("GetBackupJobReportSummary.do?LoginName=$username&BackupSetID=$setid&BackupJobID=$jobid&Cdp=$cdp");
		return $this->__parse($result);
	} 	
	
	/**
	 * List Backup Files
	 */
	function listBackupFiles($username, $setid, $jobid, $path) {
		log_message('debug', "Listing Backup Files");
		$result =  $this->__query("ListBackupFiles.do?LoginName=$username&BackupSetID=$setid&BackupJobID=$jobid&Path=$path");
		return $this->__parse($result);
	} 	

	/**
	 * List User Storage
	 */
	function listUserStorage() {
		log_message('debug', "Listing User Storage");
		$result =  $this->__query("ListUsersStorage.do");
		return $this->__parse($result);
	} 

	/**
	 * Get License
	 */
	function getLicense() {
		log_message('debug', "Getting License");
		$result =  $this->__query("GetLicense.do");
		return $this->__parse($result);
	} 

	/**
	 * Get Replication Mode
	 */
	function getReplicationMode($host = '') {
		log_message('debug', "Getting Replication Mode");
		$result =  $this->__query("GetReplicationMode.do?Host=$host");
		return $this->__parse($result);
	} 	
	
	/**
	 * Send Forgot Password Email
	 */
	function sendForgotPwdEmail($username) {
		log_message('debug', "Sending Forgot Password Email");
		return $this->__query("SendForgotPwdEmail.do?LoginName=$username");
	} 	
	
	/**
	 * Delete User
	 */
	function deleteUser($username) {
		log_message('debug', "Deleting User");
		return $this->__query("RemoveUser.do?LoginName=$username");
	} 

	/**
	 * Delete Backup Set
	 */
	function deleteBackupSet($username, $setid) {
		log_message('debug', "Deleting User");
		return $this->__query("DeleteBackupSet.do?LoginName=$username&BackupSetID=$setid");
	}	

	/**
	 * API Query
	 *
	 * Run an API query against OBS 
	 */
	function __query($url) {

		$result = '';
		$response = '';

		// Generate the full, authenticated URL
		$uri_concat = (strstr($url, "?")) ? '&' : '?';
		$url = $this->server['path'] . $url . $uri_concat . "SysUser=" . $this->server['user'] . "&SysPwd=" . $this->server['pass'];

		log_message('debug', "Attempting connection to ".$this->server['host']." on port ".$this->server['port']);

		// Try to connect to the server
		$fp = @fsockopen($this->server['protocol'] .'://' . $this->server['host'], $this->server['port'], $errno, $errstr, $this->server['timeout']); 
		if($fp) { 
			// Make request
			log_message('debug', "Sending request to " . $this->server['host']);
			$headers  = "GET $url HTTP/1.1\r\n";
			$headers .= "Host: $this->server['host']\r\n";
			$headers .= "Connection: Close\r\n\r\n";
			fwrite($fp, $headers);

			// Get response
			log_message('debug', "Getting response from " . $this->server['host']);
			while (!feof($fp)) {
				$response .= fgets($fp, 128);
			}
			fclose($fp);  

			// Check status is 200
			$status_regex = "/HTTP\/1\.\d\s(\d+)/";
			if(!preg_match($status_regex, $response, $matches) || $matches[1] != 200) {   
				log_message('error', $this->server['host'] . " reports status $matches[1]");
				$this->error='HTTP status $matches[1]';
				return false;
			}
		} else {        
			// Server connection failed
			log_message('error', "Connection failed. $errstr ($errno)");
			return false;
		}    

		// Grab the result
		$parts = explode("\r\n\r\n", $response);  
		$result = $parts[1];

		// Was there any data?
		if( !$result ) {
			log_message('error', $this->server['host'] . " sent empty response");
			$this->error='empty response';
			return false;
		}

		// Strip the headers from the reply
		$b_start = strpos($result, "\r\n\r\n");
		$result = trim(substr($result, $b_start));

		// Check for error
		if(substr($result,0,5)=="<err>") {
			$debug = debug_backtrace();
			$error = trim(strip_tags($result));
			log_message('error', "AhsayAPI->" . $debug[1]['function'] . "() error: $error");
			$this->error=$error;
			return false;
		}

		// Return whatever the server said
		log_message('debug', 'AhsayAPI Success');
		return $result;
	}    

	/**
	 * Parse XML
	 *
	 * Parse the resultant XML into php array(s)
	 *
	 * TODO: expand this so its compatible with < PHP 5
	 */   
	function __parse($xml) {
		if( !function_exists('simplexml_load_string') ) {
			// All for naught if you don't have simplexml, sorry
			show_error('AhsayAPI Wrapper Requies PHP 5', 500);
			exit;
		}

		// Load the XML using SimpleXML (PHP 5 only)
		$xmlobj = simplexml_load_string($xml);
		
		// TODO: verify this is a sufficient check of single/multiple records
		if( $xmlobj->attributes() ) {
			// Looks like this dataset is a single record
			$data = $this->__node($xmlobj);
		} else {
			// Multiple records, loop through all of 'em
			foreach( $xmlobj as $node ) {
				$data[] = $this->__node($node);
			}		
		}

		// All done, move along		
		return $data;
	}
	
	/**
	 * Format Node
	 *
	 * Takes a SimpleXMLElement Object and flattens it
	 * into a usable array structure
	 */	
	function __node($node) {
		$nodeData = $record = array(); 			
		foreach($node->attributes() as $key => $value) {
			$nodeData[strtolower($key)] = (string)$value;
		}
		$record[$node->getName()] = $nodeData;
		
		// Parse subnodes
		foreach( $node as $subnode ) {
			$nodeData = array(); 
			foreach($subnode->attributes() as $key => $value) {
				$nodeData[strtolower($key)] = (string)$value;
			}
			$record[$subnode->getName()][] = $nodeData;
		}	

		return $record;
	}

}
?>