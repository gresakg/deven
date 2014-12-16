<?php

$hosts = file("/etc/hosts");

$i=0;

foreach ($hosts as $line) {
	
	$line = trim($line);
	
	if(empty($line)) continue;
	
	if(substr($line, 0,1)=="#") {
		$line = trim($line,"#");
		
		$line = preg_split("/[\s\t]+/", $line);
		$ip = array_shift($line);
		if(is_ipv4($ip)) {
			$disabled['ipv4'][$ip] = $line;
			continue;
		}
		if(is_ipv6($ip)) {
			$disabled['ipv6'][$ip] = $line;
			continue;
		}
		$comment_line[] = "# ".implode(" ",$line);
		continue;
	} 
	$line = preg_split("/[\s\t]+/", $line);
	$ip = array_shift($line);
	
	// preveri če ip obstaja, da ne prepiše
	if(is_ipv4($ip)) {
		$entry['ipv4'][$ip] = $line;
		continue;
	}
	if(is_ipv6($ip)) {
		$entry['ipv6'][$ip] = $line;
		continue;
	}
	
	$i++;
		
}

$output['disabled'] = $disabled;
$output['comment'] = $comment_line;
$output['entry'] = $entry;

print_r($output);

function is_ipv6($ip) {
	if( filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ){
		return true;
	}
	else {
		return false;
	}
}

function is_ipv4($ip) {
	if( filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ){
		return true;
	}
	else {
		return false;
	}
}