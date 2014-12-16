#!/usr/bin/php
<?php

if(PHP_SAPI !== "cli") {
	die("This script should be used on command line interface! Terminating. Bye!\n\n");
}

define("version", "0.50");

require_once __DIR__.'/vendor/autoload.php';


class CreateCommand extends ConsoleKit\Command
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

class VersionCommand extends ConsoleKit\Command {
	public function execute(array $args, array $options = array())
    {
        $this->writeln("DevEn version ".version, ConsoleKit\Colors::GREEN);
    }
}

class HelpCommand extends ConsoleKit\Command {
	
	public function execute(array $args, array $options = array())
    {
        $this->writeln("DevEn version ".version, ConsoleKit\Colors::GREEN);
    }
}

$console = new ConsoleKit\Console();
$console->addCommand('CreateCommand');
$console->addCommand('VersionCommand');
$console->addCommand('HelpCommand');
$console->run();

function is_root() {
	return !posix_geteuid();
}
