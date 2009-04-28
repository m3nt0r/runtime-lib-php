<?php
/**
 * Runtime: Methods for getting various system details
 *
 * Allows you to detect extensions, system os, script ownership,
 * pid, modify environment variables and manage include paths
 * 
 * Licensed under The MIT License
 * 
 * PHP versions 4 and 5
 * 
 * @version 0.1
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @copyright 2008-2009 (c) Kjell Bublitz
 * @link http://cakealot.com Authors Weblog
 * @link http://github.com/m3nt0r/cake-bits Components Repository
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package libs
 */

/**
 * Runtime Class
 * 
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @example Runtime::extensions();
 * @package libs
 */
class Runtime {
		
	/**
	 * Return a array with all currently loaded extensions
	 * 
	 * @param boolean $includeZend php5.2.4 only, include zend extensions
	 * @return array
	 */	
	function extensions($includeZend = false) {		
		if (version_compare(PHP_VERSION, '5.2.4', '>=')) {
			$extensions = get_loaded_extensions($includeZend);
		} else {
			$extensions = get_loaded_extensions();
		}
		return $extensions;
	}
	
	/**
	 * Check if given extension is loaded into PHP
	 * 
	 * @param string $extensionName Name of the extension (example: curl)
	 * @param boolean $includeZend php5.2.4 only, include zend extensions
	 * @return boolean
	 */
	function extensionLoaded($extensionName, $includeZend = false) {
		$extensions = Runtime::extensions($includeZend);
		$loadedExtensions = array_map('strtolower', $extensions);
		return (in_array(strtolower($extensionName), $loadedExtensions));
	}
	
	/**
	 * Returns the verison of a loaded extension. 
	 * 
	 * Some may not provide a version string, even tho they are loaded
	 *
	 * @param string $extensionName case-sensitive
	 * @return mixed string || false
	 */
	function extensionVersion($extensionName) {
		return phpversion($extensionName);
	}
	
	/**
	 * Retrieve a alphabetically sorted array of all functions an extension offers
	 *
	 * Returns false if the ext has no functions to call or if the ext isn't loaded
	 * 
	 * @param string $extensionName
	 * @return mixed array || false
	 */
	function extensionFunctions($extensionName) {
		$funcs = get_extension_funcs(strtolower($extensionName));
		if (is_array($funcs)) sort($funcs, SORT_ASC);
		return $funcs;
	}
	
	/**
	 * Check if the script is called on command line
	 *
	 * @return boolean
	 */
	function isCommandLine() {
		Runtime::_requires('4.0.1', 'isCommandLine');	
		return (substr(php_sapi_name(), 0, 3) == 'cli');
	}
	
	/**
	 * Check if current server interface is CGI and/or FastCGI
	 *
	 * @return boolean
	 */
	function isCommonGateway() {
		Runtime::_requires('4.0.1', 'isCommonGateway');
		return (substr(php_sapi_name(), 0, 3) == 'cgi');
	}
	
	/**
	 * Return the current Operating System
	 * 
	 * @return string Example: Linux, FreeBSD, etc..
	 */
	function operatingSystem() {
		return PHP_OS;
	}
	
	/**
	 * Convenience method: Return boolean if operating system is Windows
	 *
	 * @return boolean
	 */
	function systemIsWindows() {
		return (strtolower(substr(PHP_OS, 0, 3)) === 'win');
	}
	
	/**
	 * Convenience method: Return boolean if operating system is Linux
	 *
	 * @return boolean
	 */	
	function systemIsLinux() {
		return (strtolower(PHP_OS) === 'linux');
	}
	
	/**
	 * Convenience method: Return boolean if operating system is Mac (darwin)
	 *
	 * @return boolean
	 */	
	function systemIsMac() {
		return (strtolower(PHP_OS) === 'darwin');
	}
	
	/**
	 * Get the current PHP Version
	 *
	 * @param boolean $noDots If true all dots will be removed from the string
	 * @return string
	 */
	function phpVersion($noDots = false) {
		return $noDots ? str_replace('.', '', phpversion()) : phpversion();	
	}
	
	/**
	 * Get the current Zend Engine Version
	 *
	 * @param boolean $noDots If true all dots will be removed from the string
	 * @return string
	 */
	function zendVersion($noDots = false) {
		return $noDots ? str_replace('.', '', zend_version()) : zend_version();
	}
	
	/**
	 * Return a array with all php include paths
	 *
	 * @return array
	 */
	function includePaths() {
		Runtime::_requires('4.3.0', 'includePaths');
		return array_map('trim', explode(PATH_SEPARATOR, get_include_path()));				
	}
	
	/**
	 * Add a new path to the include path 
	 *
	 * @param mixed $path Single path or a array of paths
	 */
	function includePathAdd($path) {
		Runtime::_requires('4.3.0', 'includePathAdd');
		if (empty($path)) return false;
		$paths = Runtime::includePaths();
		if (is_array($path)) {
			$paths = array_merge($paths, $path);
		} else {
			$paths[] = $path;
		}
		set_include_path(join(PATH_SEPARATOR, $paths));
		return $paths;
	}
	
	/**
	 * Remove a path from the include path 
	 *
	 * Returns false if path is not found OR if there is just one path set
	 * 
	 * @param string $path
	 * @return boolean
	 */	
	function includePathRemove($path) {
		Runtime::_requires('4.3.0', 'includePathDelete');
		$paths = Runtime::includePaths();
		if (!in_array($path, $paths) || (count($paths) == 1)) return false;
		unset($paths[array_search($path, $paths)]);
		set_include_path(join(PATH_SEPARATOR, $paths));
		return $paths;
	}
	
	/**
	 * Restore the include path to php.ini value
	 *
	 * @return void
	 */
	function includePathRestore() {
		ini_restore('include_path');
	}
	
	/**
	 * Returns the username of the current PHP scripts owner
	 *
	 * @return string
	 */
	function currentScriptOwner() {
		return get_current_user();
	}
	
	/**
	 * Get the current Process ID (pid)
	 *
	 * @return string
	 */
	function currentProcessId() {
		return getmypid();
	}
	
	/**
	 * Return the path to the currently active php.ini
	 *
	 * @return unknown
	 */
	function phpConfigPath() {
		Runtime::_requires('5.2.4', 'phpConfigPath');
		return php_ini_loaded_file();
	}
	
	
	/**
	 * Set an environment variable
	 *
	 * @param string $name
	 * @param string $value
	 * @return boolean
	 */
	function environmentSet($name, $value) {
		if (!is_string($name) || !is_string($value)) return false;
		return putenv(strtoupper($name).'='.$value);
	}
	
	/**
	 * Delete a environment variable
	 *
	 * @param string $name
	 * @return boolean
	 */
	function environmentDelete($name) {
		if (!is_string($name)) return false;
		return putenv(strtoupper($name));
	}
	
	/**
	 * Return a environment variable
	 *
	 * @param unknown_type $name
	 * @return mixed string || false if not found
	 */
	function environmentRead($name) {
		if (!is_string($name)) return false;
		return getenv(strtoupper($name));
	}
	
	/**
	 * Returns an array with all modules currently loaded
	 *
	 * @return array
	 */
	function apacheModules() {
		Runtime::_requires('4.3.2', 'apacheModules');
		return array_map('strtolower', apache_get_modules());
	}
	
	/**
	 * Test if a specific module is loaded
	 *
	 * @param string $moduleName
	 * @return boolean
	 */
	function apacheModuleLoaded($moduleName) {
		Runtime::_requires('4.3.2', 'apacheModuleLoaded');	
		if (Runtime::isCommandLine()) return false; // no error
		$list = Runtime::apacheModules();
		return (in_array(strtolower($moduleName), $list));
	}
	
	/**
	 * Return the apache version uname
	 *
	 * @example Apache/2.0.59 (Unix) PHP/4.4.7 DAV/2 SVN/1.5.2
	 * @return string
	 */
	function apacheVersion() {
		Runtime::_requires('4.3.2', 'apacheVersion');
		if (Runtime::isCommandLine()) return false; // no error
		return apache_get_version();
	}
	
	/**
	 * Set an Apache subprocess_env variable
	 *
	 * Note: Corresponding $_SERVER variable is not changed
	 * 
	 * @param string $variable Name of the env variable
	 * @param string $value Value of the env variable
	 * @param boolean $setTopLevel is available to all layers when true
	 * @return boolean true means successful
	 */
	function apacheEnvSet($variable, $value, $setTopLevel = false) {
		Runtime::_requires('4.2.0', 'apacheEnvSet');
		if (Runtime::isCommandLine()) return false; // no error
		if (!is_string($variable) || !is_string($value)) return false;
		return apache_setenv(strtoupper($variable), $value, $setTopLevel);
	}
	
	/**
	 * Read an Apache subprocess_env variable
	 *
	 * @param string $variable Name of the env variable
	 * @param boolean $setTopLevel is available to all layers when true
	 * @param mixed string || false on failure 
	 */
	function apacheEnvRead($variable, $getTopLevel = false) {
		Runtime::_requires('4.3.0', 'apacheEnvRead');
		if (Runtime::isCommandLine()) return false; // no error
		if (!function_exists('apache_getenv')) {
			trigger_error('apacheEnvRead requires Apache2', E_USER_WARNING);
			return false;
		}
		if (!is_string($variable)) return false;
		return apache_getenv(strtoupper($variable), $getTopLevel); 
	}
	
	/**
	 * Assembles a array with current request and response headers. 
	 * 
	 * Contains 'request' and 'response' sections. 
	 * The array keys are normalized with ucwords.
	 *
	 * @return array
	 */
	function apacheHeaders() {
		Runtime::_requires('4.3.0', 'apacheHeaders');
		if (Runtime::isCommandLine()) return false; // no error
		if (Runtime::isCommonGateway()) {
			trigger_error('apacheHeaders requires PHP to be installed as module', E_USER_WARNING);
			return false;
		}
		$request = apache_request_headers();
		$response = apache_response_headers();
		
		$request = array_combine(array_map('ucwords', array_keys($request)), $request);
		$response = array_combine(array_map('ucwords', array_keys($response)), $response);

		return compact('request', 'response');
	}
	
	/**
	 * Version compare shorthand with unified error message. Triggers E_USER_WARNING.
	 * 
	 * @access protected
	 * @param string $version PHP Version string
	 * @param string $func Name of the function
	 */
	function _requires($version, $func) {
		if (version_compare(PHP_VERSION, $version, '<')) {
			trigger_error($func.' is not available in this version of PHP', E_USER_WARNING);
		}
	}
	
	
}
