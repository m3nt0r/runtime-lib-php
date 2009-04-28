<?php

#require
require('../runtime.php');


#debug	
if (!function_exists('pr')) {
	function pr($var) { 
		if (!Runtime::isCommandLine()) echo "<pre>";
		print_r($var); echo "\n"; 
		if (!Runtime::isCommandLine()) echo "</pre>"; 
	}
}

/**
 * Please note:
 *   - Some functions will show you errors if your webserver does not support them
 *   - I could successfully run this script on Apache/2.0.59 (Unix) PHP/4.4.7  
 *   - I tested it in both: webbrowser and commandline.
 *   - I don't call all available methods here. There is more in the lib that does not need a demo
 */


#
# PHP SPECIFIC
#
echo "\n\n".'<h3>PHP specific</h3>'."\n";

	// should something like '5.2.1'
	echo "\n".'phpVersion'."\n";
	pr (Runtime::phpVersion());

	// should show something like 'Linux', 'WinNT', etc..
	echo "\n".'operatingSystem'."\n";
	pr (Runtime::operatingSystem());
		
	// should show the owners username
	echo "\n".'currentScriptOwner'."\n";
	pr (Runtime::currentScriptOwner());
	
	// should show a integer value
	echo "\n".'currentProcessId'."\n";
	pr (Runtime::currentProcessId());
	
	// should show false
	echo "\n".'extensionLoaded: i_am_not_loaded'."\n";
	pr (Runtime::extensionLoaded('i_am_not_loaded') ? 'true' : 'false');

	// should show true (if you really have BC Math loaded)
	echo "\n".'extensionLoaded: bcmath'."\n";
	pr (Runtime::extensionLoaded('bcmath') ? 'true' : 'false');
	

#
# APACHE SPECIFIC
#
echo "\n\n".'<h3>Apache specific</h3>'."\n";
	
	// should show default apache uname
	echo "\n".'apacheVersion'."\n";
	pr (Runtime::apacheVersion());
	
	// should show 'localhost' or something
	echo "\n".'apacheEnvRead: SERVER_NAME'."\n"; 
	pr (Runtime::apacheEnvRead('SERVER_NAME'));
	
	// should show 'true'
	echo "\n".'apacheEnvSet: MY_VAR - foobar'."\n";
	pr (Runtime::apacheEnvSet('MY_VAR', 'foobar') ? 'true' : 'false');
	
	// should show 'foobar'
	echo "\n".'apacheEnvRead: MY_VAR'."\n";
	pr (Runtime::apacheEnvRead('MY_VAR'));

	// should show false
	echo "\n".'apacheModuleLoaded: mod_not_exists'."\n";
	pr (Runtime::apacheModuleLoaded('mod_not_exists') ? 'true' : 'false');
	
	// should show 'true'
	echo "\n".'apacheModuleLoaded: core'."\n";
	pr (Runtime::apacheModuleLoaded('core') ? 'true' : 'false');
	
#
# ENV SPECIFIC
#
echo "\n\n".'<h3>Env specific</h3>'."\n";
	
	// should show 'localhost' or something
	echo "\n".'environmentRead: SERVER_NAME'."\n"; 
	pr (Runtime::environmentRead('SERVER_NAME'));
	
	// should show 'true'
	echo "\n".'environmentSet: MY_VAR - foobar'."\n";
	pr (Runtime::environmentSet('MY_VAR', 'foobar') ? 'true' : 'false');
	
	// should show 'foobar'
	echo "\n".'environmentRead: MY_VAR'."\n";
	pr (Runtime::environmentRead('MY_VAR'));
	
	// should show 'false'
	echo "\n".'environmentRead: MY_UNSET_VAR'."\n";
	pr (Runtime::environmentRead('MY_UNSET_VAR') ? 'true' : 'false');
	
#
# INCLUDE PATHS
#
echo "\n\n".'<h3>Include Path specific</h3>'."\n";
		
	// should show a list of all paths
	echo "\n".'includePaths'."\n"; 
	pr (Runtime::includePaths());
	
	// should show a list of all paths including argument
	echo "\n".'includePathAdd: /var/www/libs'."\n";
	pr (Runtime::includePathAdd('/var/www/libs'));
		
	// should show a list of all paths excluding argument
	echo "\n".'includePathRemove: /var/www/libs'."\n";
	pr (Runtime::includePathRemove('/var/www/libs'));
	
	// should show a list of all paths including the two in the argument
	echo "\n".'includePathAdd: (array) /var/www/libs, /var/www/common/includes'."\n";
	pr (Runtime::includePathAdd(array('/var/www/libs', '/var/www/common/includes')));	
	
	// restore
	Runtime::includePathRestore();
	echo "\n".'includePathRestore -> includePaths'."\n"; 
	pr (Runtime::includePaths());
	
#
# OS SPECIFIC
#
echo "\n\n".'<h3>Oparating System</h3>'."\n";
		
	// depends on your system / webserver
	echo "\n".'systemIsWindows'."\n"; 
	pr (Runtime::systemIsWindows() ? 'true' : 'false');
	
	// depends on your system / webserver
	echo "\n".'systemIsLinux'."\n"; 
	pr (Runtime::systemIsLinux() ? 'true' : 'false');
		
	// depends on your system / webserver
	echo "\n".'systemIsMac'."\n"; 
	pr (Runtime::systemIsMac() ? 'true' : 'false');
	
#
# INTERFACE SPECIFIC
#
echo "\n\n".'<h3>Interface specific</h3>'."\n";

	// depends on your system / webserver
	echo "\n".'isCommandLine'."\n"; 
	pr (Runtime::isCommandLine() ? 'true' : 'false');
	
	// depends on your system / webserver
	echo "\n".'isCommonGateway'."\n"; 
	pr (Runtime::isCommonGateway() ? 'true' : 'false');
		
	




?>