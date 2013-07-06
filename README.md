dewikinews-rss
==============

This is the German Wikinews RSS feed as deployed on Tool Labs, with one
exception: `public_html/config.php` does not contain the username and passwort
for MariaDB access. If deployed on another account on Tool Labs, this would
have to be taken from the generated `replica.my.cnf` file.

Database schema
---------------

This tool requires a MySQL/MariaDB database to store the most recent articles.
This has to be created manually. The following definition can be used:

	CREATE TABLE IF NOT EXISTS `articles` (
		`title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
		`text` mediumtext CHARACTER SET utf8 NOT NULL,
		`id` int(8) unsigned NOT NULL DEFAULT '0',
		`revid` int(8) unsigned NOT NULL DEFAULT '0',
		`touched` varchar(14) CHARACTER SET ascii DEFAULT '19700101000000',
		`published` varchar(14) CHARACTER SET ascii DEFAULT '19700101000000',
		PRIMARY KEY (`id`),
		KEY `touched` (`touched`),
		KEY `published` (`published`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `categories` (
		`category` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
		`id` int(8) unsigned NOT NULL DEFAULT '0',
		PRIMARY KEY (`category`,`id`),
		KEY `id` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

The location is defined in `public_html/config.php`.
