<?php
	/**
	 * Rest endpoint.
	 * The API REST endpoint.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 *  Start the Elgg engine
	 */
	require_once('../engine/start.php');
	global $CONFIG;
	
	$CONFIG->debug = true;
	$CONFIG->site_id = 2;
	$CONFIG->cache_path = "/tmp/cache/";

	// Register the error handler
	error_reporting(E_ALL); 
	set_error_handler('__php_api_error_handler');
	
	// Register a default exception handler
	set_exception_handler('__php_api_exception_handler'); 
	
	// Check to see if the api is available
	if ((isset($CONFIG->disable_api)) && ($CONFIG->disable_api == true))
		throw new ConfigurationException("Sorry, API access has been disabled by the administrator.");

	// Register some default PAM methods, plugins can add their own
	register_pam_handler('pam_auth_session');
	register_pam_handler('pam_auth_hmac');
	
	// Get parameter variables
	$method = get_input('method');
	$result = null;
	
	// Authenticate session
	if (pam_authenticate())
	{
		// Authenticated somehow, now execute.
		$token = "";
		$params = $_REQUEST;
		if (isset($params['auth_token'])) $token = $params['auth_token'];

		$result = execute_method($method, $params, $token);
	}
	else
		throw new SecurityException("No authentication methods were found that could authenticate this API request.");
	
	// Finally output
	if (!($result instanceof GenericResult))
		throw new APIException("API Result is of an unknown type, this should never happen.");

	// Output the result
	page_draw($method, elgg_view("api/output", array("result" => $result)));
	
?>