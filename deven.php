#!/usr/bin/php
<?php
/**
 * DevEn, developpement environement creator for local LAMP servers
 * Copyright (C) 2014  Gregor Grešak, gresak.net
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

define("nl","\n");
define("version", "0.2");

require 'config.php';

// few environement checks
if(PHP_SAPI !== "cli") {
	die("This script should be used on command line interface! Terminating. Bye!");
}

//options
$short = "";
$short .= "u:";
$short .= "p:";
$short .= "l";
$short .= "h";
$short .= "v";
$long = array(
	"list",
	"prompt",
	"help",
	"version",
);
$options = getopt($short, $long);

if(isset($options['help']) || isset($options['h'])) {
	require 'help.txt';
	echo nl;
	die;
}

echo "## DevEn version ".version." ##
Copyright (C) 2014, Gregor Grešak, gresak.net
DevEn comes with ABSOLUTELY NO WARRANTY; This is free software, 
and you are welcome to redistribute it under GPL v.2 license.".nl.nl;

if(isset($options["v"]) || isset($options["version"])) die ("DevEn version ".version.nl.nl);
if(count($argv) < 2) die("Usage: deven [options] domain.name".nl.nl."Please type deven -h for options details.".nl.nl);

// we need root privileges
if(posix_geteuid() !== 0) {
	die("You need root privileges to run this script! Please run as sudo or root.".nl.nl);
}

//list option TODO beautify!
if(isset($options['l'])||isset($options['list'])) {
	var_dump(glob($apache_config_dir."*.conf"));
	echo nl;
	die;
}

//gather information

if (isset($options['prompt'])) {
	$domain = prompt("Domain name", "required");
	$user = prompt("User", true, get_current_user());
	$path = prompt("Project path", false);
} 
else {
	$domain = array_pop($argv);
	if(empty($options['u'])) {
		$user = get_current_user();
	} else {
		$user = $options['u'];
	}
	if(isset($options['path'])) {
		$path = $options['path'];
	}
	elseif(isset($options['p'])) {
		$path = $options['p'];
	}
}

//check if domain exists
$sites_available = glob($apache_config_dir."*.conf");
if(in_array($apache_config_dir.$domain.".conf",$sites_available)) {
	die("Domain allready exists in ".$apache_config_dir.nl);
}

//does the domain name looks like one, or it could be a mistake? TODO regex check for valid characters
if(strpos($domain,".") === false) {
	$domainconfirm = prompt("You are creating a domain without TLD: ".$domain.".".nl." Please confirm "
		. "that this is what you meant by reentering the domain name or just say No ".nl,true,"no");
	if(strtolower(trim($domainconfirm))== "no") die ("Operation aborted!".nl);
	if(trim($domainconfirm) !== $domain) die("The confirmation domain and the domain first entered missmached. ".nl
		. "Please start over. Aborting operation!".nl);
}


if(empty($path)) {
	$projects_dir = "/home/".$user."/".$projects_dir;
	if(is_dir($projects_dir)===false) die($projects_dir . " does not exist. Check your config.php file.".nl.nl);
	$path = $projects_dir."/".$domain;
}

$logpath = $path."/logs";
$server_root = $path."/public_html";

// debug here if you have problems with virtualhost template
//echo nl;
//include_once 'vhtpl.php';
//echo nl;
//die;

//create directories
mkdir($path);
chown($path,$user);
chgrp($path,$user);
mkdir($logpath);
chown($logpath,$apache_user);
chgrp($logpath,$group);
mkdir($server_root);
chown($server_root,$user);
chgrp($server_root,$group);

//add initial index file to test if it works
$file = fopen($server_root."/index.php","w");
fwrite($file, "<?php phpinfo(); ?>");
fclose($file);

//write apache config file
$file = fopen($apache_config_dir.$domain.".conf","w");
ob_start();
include 'vhtpl.php';
$content = ob_get_clean();
fwrite($file, $content);
fclose($file);

//add entry to /etc/hosts
$file = fopen("/etc/hosts", "a");
fwrite($file, nl."# Line added by DevEn".nl);
fwrite($file, "127.0.0.1	".$domain.nl);
fclose($file);

//enable new site
exec("a2ensite ".$domain);

//restart apache
exec("service apache2 reload");

echo "The developpement environement for ".$domain. "is successfully created!".nl
	. "If you point your browser to $domain you should see phpinfo().".nl
	. "You can start working on your project in $server_root".nl
	. "You will find apache logs in $logpath.".nl
	. "Have fun!".nl;

// end the script with carriage return
echo nl;


function prompt($prompt, $required, $default = "") {
	echo $prompt.(empty($default)?(empty($required)?"":" (required)"):" [".$default."]").": ";
	$value = trim(fgets(STDIN));
	if($value == "") $value = $default;
	if($value == "" && ($required == "required" || $required == true)) {
		prompt($prompt,$required,$default);
	}
	
	return $value;
}