# Caffeinated

A tool for tracking your daily intake of caffeine

## Production Setup

### Requirements
- PHP 5.2.8+
- HTTP Server. For example: Apache. mod_rewrite is preferred, but by no means required.
- Database Server. MySQL (4 or greater), PostgreSQL, Microsoft SQL Server or SQLite
- Composer 1.0+
- Bower 1.3+

### Installation

1. Download the repository and extract to your web root
1. Change the values of Security.salt and Security.cipherSeed in /Config/core.php
1. From the installation root, install the dependancies using composer and bower, and setup the application using the cake shell

		composer install
		bower install
		cake setup install

1. Browse to your web server, you should have an up and running Tesla installation

### Updating

1. Make a backup of the files, and database before you start!
1. Record the values of Security.salt and Security.cipherSeed in /Config/core.php
1. Download the repository and extract to your web root, replacing any duplicate files
1. Change the values of Security.salt and Security.cipherSeed in /Config/core.php to match your old values
1. From the installation root, update all depenancies and schemas

		composer install
		bower install
		cake setup update

## Development Setup

### Requirements

- [VirtualBox](https://www.virtualbox.org/wiki/Downloads). Tested on 4.3.x.
- [Vagrant](http://www.vagrantup.com/downloads.html). Tested on 1.6+

### Environment Setup

Download and install both VirtualBox and Vagrant for your particular operating system. Should only take a few minutes on a DSL connection.

Once those are downloaded, open up a terminal. We'll need to clone this repository and startup vagrant:

	git clone https://bitbucket.org/Indemnity83/tesla
	cd tesla
	vagrant up

Vagrant will download an Ubuntu box, launch it in Virtualbox and run the bootstrap.sh script to get your development environment up and running. Once thats done you'll want to log into the virtual machine via SSH and deploy the

	cd /vagrant
	composer install --dev
	bower install

Finally, run through the cake console setup to get the database and an admin user setup.

	cd /vagrant/app
	cake setup install

Once it is done, browse to `http://192.168.33.10/` in your browser, and you should have a running instance of Tesla for development

### Database Access

phpMyAdmin is available at `http://192.168.33.10/phpmyadmin` with the following credentials:

- `root:root`

## Bugs?

File an issue.

## Branching strategy

See [A successful Git branching model](http://nvie.com/posts/a-successful-git-branching-model/) by Vincent Driessen for information on the branching strategy implemented
