# Caffeinated [![Build Status](https://travis-ci.org/Indemnity83/caffeinated.svg?branch=develop)](https://travis-ci.org/Indemnity83/caffeinated)

A tool for tracking your daily intake of caffeine

### Requirements
- PHP 5.2.8+
- HTTP Server. For example: Apache. mod_rewrite is preferred, but by no means required.
- Database Server. MySQL (4 or greater), PostgreSQL, Microsoft SQL Server or SQLite
- Composer 1.0+
- Bower 1.3+

### Installation

If your handy with a shell, this should be enough to get things going for you:

		git clone https://github.com/Indemnity83/caffeinated.git ./
		composer install
		bower install
		app/Console/cake schema create

Don't forget to change the security.salt and security.cipherseed in ./app/Config/core.php to something for your own deployment. 

The default user is `admin:admin`

### Updating

Similar to installation, use command line tools to update your local copy. You may have to merge changes to ./app/Config/core.php (sorry, its just the way that CakePHP works)

		git pull
		composer install
		bower install
		app/Console/cake schema update
