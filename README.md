# DevEn

_DevEn_ is a developpement environement creator for LAMP server focused on Debian.
When you start a project, you want to get it up and runnung without to much hassle.
DevEn creates a home folder for the project, a virtualhost for Apache, an entry to
the /etc/hosts file that points to localhost and optionaly a mysql database.

_DevEn_ is not as complete as [EasyEngine](https://rtcamp.com/easyengine/) and certainly
does not do WordPress installs, but it does set you up a developpement environment
quickly.

## Installation

Clone the repo or download and unzip to a directory of your choice. Make deven.php
executable

``
chmod +x deven.php
``

Make a soft link somwhere in your PATH

``
ln -s /path/to/deven.php /usr/local/bin/deven
``

## Usage

You must use DevEn as root (or sudo).

deven [options] domain.name

``
deven myproject.local
``

### Options

 -u sets the user. By default, current user will be used.

 -p --path project will be created using this path rather than the default one.

 -l --list list existing domains 



 -h --help outputs this help
 -v --version outputs deven version number
 --prompt gathers information using prompt rather than command line arguments


## TODO

- skip folders creation (for existing sources)
- delete
- option to create mysql database
- option to create mongodb collection
