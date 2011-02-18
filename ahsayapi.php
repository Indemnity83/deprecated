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

	/**
	 * Last Error
	 *
	 * When API calls fail, they set the error property. If your application 
	 * needs more details about an error, it can retrieve the error details
	 * from this property.
	 *
	 * @param string 
	 * @access public
	 */
	public $error;
	
	/**
	 * AhsayOBS Connection Defaults
	 *
	 * @param array
	 * @access private
	 */
	private $__defaults = array(
		'host' => 'localhost', 
		'user' => 'system', 
		'pass' => '',
		'timeout' => '30', 
		'path' => '/obs/api/',
		'protocol' => 'sslv3',
		'port' => '443',
	); 
	
	/**
	 * HTTP/1.1 Status Codes
	 *
	 * @param array
	 * @access private
	 */	
	private $__status = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		510 => 'Not Extended'
	);
	
	/**
	 * AhsayOBS Connection Paramaters
	 *
	 * @param array
	 * @access private
	 */
	private $server;

	/**
	 * Constructor
	 *
	 * @param array $params Initialization paramaters
	 * @access private
	 */
	public function __construct($params = array()) {
		$this->server = array_merge($this->__defaults, $params);                
		log_message('debug', 'Library AhsayAPI Initialized');
	}

	/**
	 * User Authorization
	 *
	 * Invokes the [Auth User] API to check whether an existing
	 * user is authorized.
	 * 
	 * @param string $params API paramaters
	 * @return boolean true if successful, false otherwise
	 * @access public
	 */
	function authUser($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'Password'		=> ''
		);

		log_message('debug', 'AhsayAPI::authUser');        
		$params = http_build_query(array_merge($defaults, $params));		
		return $this->__query("AuthUser.do?$params");
	}   
	
	/**
	 * Add a Backup Set
	 *
	 * Invokes the [Add Backup Set] API to add a new backup set.
	 * 
	 * @param string $params API paramaters
	 * @return array Backup set information if successful, boolean false otherwise
	 * @access public
	 */	
	function addBackupSet($params = array()) {
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::addBackupSet');        
		$params = http_build_query(array_merge($defaults, $params));       
		$result = $this->__query("AddBackupSet.do?$params");
		return $this->__parse($result);
	}

	/**
	 * Update a Backup Set
	 *
	 * Invokes the [Update Backup Set] API to update a backup set. 
	 * 
	 * Note: use getBackupSet() function to retrieve an existing backup set; modify
	 * the the settings as needed before invoking this function.
	 * 
	 * @param string $params API paramaters
	 * @param array $profile Entire backup set configuration including modifications
	 * @return boolean true if successful, false otherwise
	 * @access public
	 */	
	function updateBackupSet($params, $profile) {
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::updateBackupSet');        
		$params = http_build_query(array_merge($defaults, $params));    
		return $this->__query("UpdateBackupSet.do?$params", $profile);		
	}

	/**
	 * List Users
	 *
	 * Invokes the [List Users] API to get a list of all users available with AhsayOBS.
	 * 
	 * @param string $params API paramaters
	 * @return array User's information if successful, boolean false otherwise
	 * @access public
	 */		 
	function listUsers($params = array()) {   
		$defaults = array(
			'Host'			=> ''
		);

		log_message('debug', 'AhsayAPI::listUsers');        
		$params = http_build_query(array_merge($defaults, $params));
		$result = $this->__query("ListUsers.do?$params");		
		return $this->__parse($result);
	} 

	/**
	 * List Backup Sets
	 *
	 * Invokes the [List Backup Sets] API to list all the backup sets for a particular user
	 *
	 * @param string $params API paramaters
	 * @return array Backup set information if successful, boolean false otherwise
	 * @access public
	 */
	function listBackupSets($params = array()) {    
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::listBackupSets');        
		$params = http_build_query(array_merge($defaults, $params));       
		$result =  $this->__query("ListBackupSets.do?$params");   
		return $this->__parse($result);
	}  

	/**
	 * Get User
	 *
	 * Invokes the [Get User] API to get a user's account information.
	 *
	 * @param string $params API paramaters
	 * @return array User account information if successful, boolean false otherwise
	 * @access public
	 */
	function getUser($params = array()) {    
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::getUser');        
		$params = http_build_query(array_merge($defaults, $params));         
		$result =  $this->__query("GetUser.do?$params");   
		return $this->__parse($result);
	} 	

	/**
	 * Get User Storage Stats
	 *
	 * Invokes the [Getting User Storage Statistics] API to get a list of storage informatino for a user.
	 *
	 * @param string $params API paramaters
	 * @return array User's storage information if successful, boolean false otherwise
	 * @access public
	 */   
	function getUserStorageStat($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'YearMonth' 	=> ''
		);

		log_message('debug', 'AhsayAPI::getUserStorageStat');        
		$params = http_build_query(array_merge($defaults, $params)); 
		$result =  $this->__query("GetUserStorageStat.do?$params");
		return $this->__parse($result);
	}    

	/**
	 * List Backup Jobs
	 *
	 * Invokes the [List Backup Jobs] API to get a list of all backup jobs for all backup sets.
	 *
	 * @param string $params API paramaters
	 * @return array Backup job information if successful, boolean false otherwise
	 * @access public
	 */
	function listBackupJobs($params = array()) {
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::listBackupJobs');        
		$params = http_build_query(array_merge($defaults, $params)); 
		$result =  $this->__query("ListBackupJobs.do?$params");
		return $this->__parse($result);
	}

	/**
	 * Get Backup Set
	 *
	 * Invokes the [Get Backup Set] API to get a backup set.
	 *
	 * @param string $params API paramaters
	 * @return array Backup set information if successful, boolean false otherwise
	 * @access public
	 */
	function getBackupSet($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'BackupSetID' 	=> 0
		);

		log_message('debug', 'AhsayAPI::getBackupSet');        
		$params = http_build_query(array_merge($defaults, $params));
		$result =  $this->__query("GetBackupSet.do?$params");
		return $this->__parse($result);
	}    

	/**
	 * List Backup Job Status
	 *
	 * Invokes the [List Backup Job Status] API to get a list of all the backup jobs for all backup sets
	 *
	 * @param string $params API paramaters
	 * @return array Backup set information if successful, boolean false otherwise
	 * @access public
	 */
	function listBackupJobStatus($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'BackupDate' 	=> ''
		);

		log_message('debug', 'AhsayAPI::listBackupJobStatus');        
		$params = http_build_query(array_merge($defaults, $params));
		$result =  $this->__query("ListBackupJobStatus.do?$params");
		return $this->__parse($result);
	} 

	/**
	 * Get Backup Job Report
	 *
	 * Invokes the [Get Backup Job Report] API to get a backup job status.
	 *
	 * @param string $params API paramaters
	 * @return array Backup job information if successful, boolean false otherwise
	 * @access public
	 */
	function getBackupJobReport($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'BackupSetID' 	=> 0,
			'BackupJobID'	=> 0
		);

		log_message('debug', 'AhsayAPI::getBackupJobReport');        
		$params = http_build_query(array_merge($defaults, $params));
		$result =  $this->__query("GetBackupJobReport.do?$params");
		return $this->__parse($result);
	} 	
	
	/**
	 * Get Backup Job Report Summary
	 *
	 * Invokes the [Get Backup Job Report Summary] API to get a backup job status.
	 *
	 * @param string $params API paramaters
	 * @return array Backup job information if successful, boolean false otherwise
	 * @access public
	 */
	function getBackupJobReportSummary($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'BackupSetID' 	=> 0,
			'BackupJobID'	=> 0,
			'Cdp'			=> 'N'
		);

		log_message('debug', 'AhsayAPI::getBackupJobReportSummary');        
		$params = http_build_query(array_merge($defaults, $params));
		$result =  $this->__query("GetBackupJobReportSummary.do?$params");
		return $this->__parse($result);
	} 	
	
	/**
	 * List Backup Files
	 *
	 * Invokes the [List Backup Files] API to get all backup files in a given directory
	 *
	 * @param string $params API paramaters
	 * @return array Backup file information if successful, boolean false otherwise
	 * @access public
	 */
	function listBackupFiles($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'BackupSetID' 	=> 0,
			'BackupJobID'	=> 0,
			'Path'			=> ''
		);

		log_message('debug', 'AhsayAPI::listBackupFiles');        
		$params = http_build_query(array_merge($defaults, $params));
		$result =  $this->__query("ListBackupFiles.do?LoginName=$params");
		return $this->__parse($result);
	} 	

	/**
	 * List User Storage
	 *
	 * Invokes the [List Users Storage] API to get a list of storage information for all users available within AhsayOBS.
	 *
	 * @return array User's storage information if successful, boolean false otherwise
	 * @access public
	 */
	function listUserStorage() {
		log_message('debug', 'AhsayAPI::listUserStorage');
		$result =  $this->__query("ListUsersStorage.do");
		return $this->__parse($result);
	} 

	/**
	 * Get License
	 *
	 * Invokes the [List License] API to get version of AhsayOBS, used and unused licenses available within AhsayOBS
	 *
	 * @return array License information if successful, boolean false otherwise	 
	 * @since AhsayOBS v5.2.5.0
	 * @access public
	 */
	function getLicense() {
		log_message('debug', 'AhsayAPI::getLicense');
		$result =  $this->__query("GetLicense.do");
		return $this->__parse($result);
	} 

	/**
	 * Get Replication Mode
	 *
	 * Invokes the [List Replication Mode] API to get the mode of replication available within AhsayOBS.
	 *
	 * @param string $params API paramaters
	 * @return array License information if successful, boolean false otherwise	 
	 * @since AhsayOBS v5.2.5.0
	 * @access public
	 */
	function getReplicationMode($params = array()) {
		$defaults = array(
			'Host'		=> ''
		);

		log_message('debug', 'AhsayAPI::getReplicationMode');        
		$params = http_build_query(array_merge($defaults, $params)); 
		$result =  $this->__query("GetReplicationMode.do?$params");
		return $this->__parse($result);
	} 	
	
	/**
	 * Send Forgot Password Email
	 *
	 * Invokes the [Send Forgot Password Email] API to invoke the sending forgot password email operation in AhsayOBS
	 *
	 * @param string $params API paramaters
	 * @return boolean true if successful, false otherwise
	 * @since AhsayOBS v5.2.5.0
	 * @access public
	 */
	function sendForgotPwdEmail($params = array()) {
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::sendForgotPwdEmail');        
		$params = http_build_query(array_merge($defaults, $params)); 
		return $this->__query("SendForgotPwdEmail.do?$params");
	} 	
	
	/**
	 * Delete User
	 *
	 * Invokes the [Remove User] API to remove an existing user from an AhsayOBS.
	 *
	 * @param string $params API paramaters
	 * @return boolean true if successful, false otherwise
	 * @access public
	 */
	function deleteUser($params = array()) {
		$defaults = array(
			'LoginName'		=> ''
		);

		log_message('debug', 'AhsayAPI::deleteUser');        
		$params = http_build_query(array_merge($defaults, $params)); 
		return $this->__query("RemoveUser.do?$params");
	} 

	/**
	 * Delete Backup Set
	 *
	 * Invokes the [Delete Backup Set] API to delete a backup set for a particular user
	 *
	 * @param string $params API paramaters
	 * @return boolean true if successful, false otherwise
	 * @access public
	 */
	function deleteBackupSet($params = array()) {
		$defaults = array(
			'LoginName'		=> '',
			'BackupSetID'	=> 0
		);

		log_message('debug', 'AhsayAPI::deleteBackupSet');        
		$params = http_build_query(array_merge($defaults, $params)); 
		return $this->__query("DeleteBackupSet.do?$params");
	}	

	/**
	 * API Query
	 *
	 * Run an API query against OBS
	 * 
	 * @param string $url Restful query URL
	 * @param array $postVars optional Additional data for POST operations.
	 * @return object SimpleXMLElement response from AhsayOBS if successful, false otherwise
	 * @access private
	 */
	private function __query($url, $postVars = false) {

		$result = '';
		$response = '';

		// Generate the full, authenticated URL
		$uri_concat = (strstr($url, "?")) ? '&' : '?';
		$url = $this->server['path'] . $url . $uri_concat . "SysUser=" . $this->server['user'] . "&SysPwd=" . $this->server['pass'];

		// Try to connect to the server
		log_message('debug', "Attempting connection to ".$this->server['host']." on port ".$this->server['port']." using ".$this->server['protocol']);
		$fp = @fsockopen($this->server['protocol'] .'://' . $this->server['host'], $this->server['port'], $errno, $errstr, $this->server['timeout']); 
		if(!$fp) {        
			$this->error="Connection failed. $errstr ($errno)";
			log_message('error', $this->error);
			return false;
		}    
		
		// Make request
		if( $postVars ) {
			$content = http_build_query($postVars);			
			$headers  = "POST $url HTTP/1.1\r\n";
			$headers .= "Host: $this->server['host']\r\n";
			$headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$headers .= "Content-Length: ".strlen($content)."\r\n";
			$headers .= "Connection: Close\r\n\r\n";
			fwrite($fp, $headers);
			fwrite($fp, $content);
		} else {
			$headers  = "GET $url HTTP/1.1\r\n";
			$headers .= "Host: $this->server['host']\r\n";
			$headers .= "Connection: Close\r\n\r\n";
			fwrite($fp, $headers);
		}			

		// Get response
		while (!feof($fp)) {
			$response .= fgets($fp, 128);
		}
		fclose($fp);  

		// Check status is 200
		$status_regex = "/HTTP\/1\.\d\s(\d+)/";
		if(!preg_match($status_regex, $response, $matches) || $matches[1] != 200) {   
			$status = $this->__status[$matches[1]];
			$this->error="Server responded $matches[1] $status";
			log_message('error', $this->error);
			return false;
		}

		// Grab the result
		$parts = explode("\r\n\r\n", $response);  
		$result = $parts[1];

		// Was there any data?
		if( !$result ) {
			$this->error='Server returned empty response';
			log_message('error', $this->error);
			return false;
		}

		// Strip the headers from the reply
		$b_start = strpos($result, "\r\n\r\n");
		$result = trim(substr($result, $b_start));

		// Check for error from AhsayOBS
		if(substr($result,0,5)=="<err>") {
			$debug = debug_backtrace();
			$this->error = trim(strip_tags($result));
			log_message('error', "AhsayAPI->" . $debug[1]['function'] . "(): ".$this->error);
			return false;
		}

		// Return whatever the server said
		return $result;
	} 

	/**
	 * Parse XML
	 *
	 * Parse a SimpleXMLElement object into multi-dimentional php array
	 * 
	 * @param object $xml SimpleXMLElement to be parsed
	 * @return array
	 * @access private
	 */   
	private function __parse($xml) {
		if( !$xml || $xml === false ) {
			// These aren't the droids your looking for...
			return false;
		}
	
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
	 * Takes a single SimpleXMLElement node and formats it into a usable array structure
	 *
	 * @param object $node Single SimpleXMLElement node to be flattened
	 * @return array
	 * @access private	 
	 */	
	private function __node($node) {
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