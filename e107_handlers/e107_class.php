<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_handlers/e107_class.php,v $
|     $Revision: 1.9 $
|     $Date: 2007-08-13 19:56:35 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

/**
 * Core e107 class
 *
 */
class e107{

	var $server_path;
	var $e107_dirs;
	var $http_path;
	var $https_path;
	var $base_path;
	var $file_path;
	var $relative_base_path;
	var $_ip_cache;
	var $_host_name_cache;
	/**
	 * e107 class constructor
	 *
	 * @param array $e107_paths
	 * @param string $e107_root_path
	 * @return e107
	 */
	function e107($e107_paths, $e107_root_path){
		$this->e107_dirs = $e107_paths;
		$this->set_paths();
		$this->file_path = $this->fix_windows_paths($e107_root_path)."/";
	}

	function set_base_path()
	{
		global $pref;
		$this->base_path = ($pref['ssl_enabled']==1 ?  $this->https_path : $this->http_path);
	}

	function set_paths(){
		global $DOWNLOADS_DIRECTORY, $ADMIN_DIRECTORY, $IMAGES_DIRECTORY, $THEMES_DIRECTORY, $PLUGINS_DIRECTORY,
		$FILES_DIRECTORY, $HANDLERS_DIRECTORY, $LANGUAGES_DIRECTORY, $HELP_DIRECTORY, $CACHE_DIRECTORY,
		$NEWSIMAGES_DIRECTORY, $CUSTIMAGES_DIRECTORY, $UPLOADS_DIRECTORY;

		$path = ""; $i = 0;
		while (!file_exists("{$path}class2.php")) {
			$path .= "../";
			$i++;
		}
		if($_SERVER['PHP_SELF'] == "") { $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME']; }

		$http_path = dirname($_SERVER['PHP_SELF']);
		$http_path = explode("/", $http_path);
		$http_path = array_reverse($http_path);
		$j = 0;
		while ($j < $i) {
			unset($http_path[$j]);
			$j++;
		}
		$http_path = array_reverse($http_path);
		$this->server_path = implode("/", $http_path)."/";
		$this->server_path = $this->fix_windows_paths($this->server_path);

		if ($this->server_path == "//") {
			$this->server_path = "/";
		}
		$this->relative_base_path = $path;
		$this->http_path = "http://{$_SERVER['HTTP_HOST']}{$this->server_path}";
		$this->https_path = "https://{$_SERVER['HTTP_HOST']}{$this->server_path}";
		$this->file_path = $path;

		if(!defined("e_HTTP") || !defined("e_ADMIN") )
		{
			define("e_HTTP", $this->server_path);
			define("e_BASE", $this->relative_base_path);

//
// HTTP relative paths
//
			define("e_ADMIN", e_BASE.$ADMIN_DIRECTORY);
			define("e_IMAGE", e_BASE.$IMAGES_DIRECTORY);
			define("e_THEME", e_BASE.$THEMES_DIRECTORY);
			define("e_PLUGIN", e_BASE.$PLUGINS_DIRECTORY);
			define("e_FILE", e_BASE.$FILES_DIRECTORY);
			define("e_HANDLER", e_BASE.$HANDLERS_DIRECTORY);
			define("e_LANGUAGEDIR", e_BASE.$LANGUAGES_DIRECTORY);
			define("e_DOCS", e_BASE.$HELP_DIRECTORY);
//
// HTTP absolute paths
//
			define("e_ADMIN_ABS", e_HTTP.$ADMIN_DIRECTORY);
			define("e_IMAGE_ABS", e_HTTP.$IMAGES_DIRECTORY);
			define("e_THEME_ABS", e_HTTP.$THEMES_DIRECTORY);
			define("e_PLUGIN_ABS", e_HTTP.$PLUGINS_DIRECTORY);
			define("e_FILE_ABS", e_HTTP.$FILES_DIRECTORY);
			define("e_HANDLER_ABS", e_HTTP.$HANDLERS_DIRECTORY);
			define("e_LANGUAGEDIR_ABS", e_HTTP.$LANGUAGES_DIRECTORY);

			if(isset($_SERVER['DOCUMENT_ROOT']))
			{
			  define("e_DOCROOT", $_SERVER['DOCUMENT_ROOT']."/");
			}
			else
			{
			  define("e_DOCROOT", false);
			}
			define("e_DOCS_ABS", e_HTTP.$HELP_DIRECTORY);

			if($CACHE_DIRECTORY) {
            	define("e_CACHE", e_BASE.$CACHE_DIRECTORY);
			} else {
            	define("e_CACHE", e_BASE.$FILES_DIRECTORY."cache/");
			}

			if($NEWSIMAGES_DIRECTORY) {
            	define("e_NEWSIMAGE", e_BASE.$NEWSIMAGES_DIRECTORY);
			} else {
            	define("e_NEWSIMAGE", e_IMAGE."newspost_images/");
			}

			if($CUSTIMAGES_DIRECTORY) {
            	define("e_CUSTIMAGE", e_BASE.$CUSTIMAGES_DIRECTORY);
			} else {
            	define("e_CUSTIMAGE", e_IMAGE."custom/");
			}

			if ($DOWNLOADS_DIRECTORY{0} == "/") {
				define("e_DOWNLOAD", $DOWNLOADS_DIRECTORY);
			} else {
				define("e_DOWNLOAD", e_BASE.$DOWNLOADS_DIRECTORY);
			}

			if(!$UPLOADS_DIRECTORY)
			{
            	$UPLOADS_DIRECTORY = $FILES_DIRECTORY."public/";
			}

			if ($UPLOADS_DIRECTORY{0} == "/") {
				define("e_UPLOAD", $UPLOADS_DIRECTORY);
			} else {
				define("e_UPLOAD", e_BASE.$UPLOADS_DIRECTORY);
			}
		}
	}

	function fix_windows_paths($path) {
		$fixed_path = str_replace(array('\\\\', '\\'), array('/', '/'), $path);
		$fixed_path = (substr($fixed_path, 1, 2) == ":/" ? substr($fixed_path, 2) : $fixed_path);
		return $fixed_path;
	}

	/**
	 * Check if current user is banned
	 *
	 */
	function ban() {
		global $sql, $e107, $tp, $pref;
		$ban_count = $sql->db_Count("banlist");
		if($ban_count)
		{
			$ip = $this->getip();
			$tmp = explode(".",$ip);
			$wildcard =  $tmp[0].".".$tmp[1].".".$tmp[2].".*";
			$wildcard2 = $tmp[0].".".$tmp[1].".*.*";

			if(varsettrue($pref['enable_rdns']))
			{
				$tmp = $e107->get_host_name(getenv('REMOTE_ADDR'));
				preg_match("/[\w]+\.[\w]+$/si", $tmp, $match);
				$bhost = (isset($match[0]) ? " OR banlist_ip='".$tp -> toDB($match[0], true)."'" : "");
			}
			else
			{
				$bhost = "";
			}

			if ($ip != '127.0.0.1')
			{
				if ($sql->db_Select("banlist", "*", "banlist_ip='".$tp -> toDB($_SERVER['REMOTE_ADDR'], true)."' OR banlist_ip='".USEREMAIL."' OR banlist_ip='{$ip}' OR banlist_ip='{$wildcard}' OR banlist_ip='{$wildcard2}' {$bhost}"))
				{
				  header("HTTP/1.1 403 Forbidden", true);
					// enter a message here if you want some text displayed to banned users ...
					exit();
				}
			}
		}
	}

	/**
	 * Get the current user's IP address
	 *
	 * @return string
	 */
	function getip() {
		if(!$this->_ip_cache){
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$ip=$_SERVER['REMOTE_ADDR'];
				if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3)) {
				$ip2 = array('#^0\..*#',
					'#^127\..*#', 							// Local loopbacks
					'#^192\.168\..*#', 						// RFC1918 - Private Network
					'#^172\.(?:1[6789]|2\d|3[01])\..*#', 	// RFC1918 - Private network
					'#^10\..*#', 							// RFC1918 - Private Network
					'#^169\.254\..*#', 						// RFC3330 - Link-local, auto-DHCP
					'#^2(?:2[456789]|[345][0-9])\..*#'		// Single check for Class D and Class E
					);
					$ip = preg_replace($ip2, $ip, $ip3[1]);
				}
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			if ($ip == "") {
				$ip = "x.x.x.x";
			}
			$this->_ip_cache = $ip;
		}
		return $this->_ip_cache;
	}

	function get_host_name($ip_address) {
		if(!$this->_host_name_cache[$ip_address]) {
			$this->_host_name_cache[$ip_address] = gethostbyaddr($ip_address);
		}
		return $this->_host_name_cache[$ip_address];
	}

	// Return a memory value formatted helpfully
	function ret_memory_text($memusage)
	{
	  $memunit = CORE_LAN_B;
	  if ($memusage > 65536)
	  {
		$memusage = $memusage / 1024; // more than 64k, show in k
		$memunit = CORE_LAN_KB;
	  }
	  if ($memusage > 1024)
	  { /* 1.002 mb, etc */
		$memusage = $memusage / 1024;
		$memunit = CORE_LAN_MB;
	  }
	  if ($memusage > 1024)
	  { /* show in GB if >1GB */
		$memusage = $memusage / 1024;
		$memunit = CORE_LAN_GB;
	  }
	  return (number_format($memusage, ($memunit=='b'? 0 : 3)).$memunit);
	}
	
	
	/**
	 * Get the current memory usage of the code
	 *
	 * @return string memory usage
	 */
	function get_memory_usage(){
		if(function_exists("memory_get_usage"))
		{
	      $ret = $this->ret_memory_text(memory_get_usage());
		  // With PHP>=5.2.0, can show peak usage as well
	      if (function_exists("memory_get_peak_usage")) $ret .= '/'.$this->ret_memory_text(memory_get_peak_usage(TRUE));
		  return $ret;
		} 
		else 
		{
		  return ('Unknown');
		}
	}

}
?>
