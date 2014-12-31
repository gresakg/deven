#!/usr/bin/php
<?php

if(PHP_SAPI !== "cli") {
	die("This script should be used on command line interface! Terminating. Bye!\n\n");
}

define("version", "0.50");

require_once __DIR__.'/vendor/autoload.php';

class Deven extends ConsoleKit\Command {
	
	private $splash; 
	
	protected $config;
	
	 public function __construct(\ConsoleKit\Console $console)
	 {
		 parent::__construct($console);
		 $this->setSplash(version);
		 $this->writeln($this->splash);
	 }
	 
	 public function setSplash($version) {
		 $this->splash = "## DevEn version ".$version." ## \n".
			"Copyright (C) 2014, Gregor Grešak, gresak.net \n".
			"DevEn comes with ABSOLUTELY NO WARRANTY; This is free software, \n".
			"and you are welcome to redistribute it under GPL v.2 license.";
	 }
	 
	 public function setConfig() {
		 $this->config = include 'config.php';
	 }
	
}


class CreateCommand extends Deven
{
    public function execute(array $args, array $options = array())
    {
        if(!is_root()) {
			$this->writeln('You need root privileges to run this script! Please run as sudo or root.', ConsoleKit\Colors::RED);
		return;
		}
		$this->writeln("Hello!");
    }
}

class VersionCommand extends Deven {
	public function execute(array $args, array $options = array())
    {
        $this->writeln("DevEn version ".version, ConsoleKit\Colors::GREEN);
    }
}

$console = new ConsoleKit\Console();
$console->addCommand('CreateCommand');
$console->addCommand('VersionCommand');
$console->run();

function is_root() {
	return !posix_geteuid();
}
