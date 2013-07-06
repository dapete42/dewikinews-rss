<?php

// Database configuration for general RSS feed
$dbHost = 'tools-db';
$dbUsername = 'NOT INCLUDED HERE';
$dbPassword = 'NOT INCLUDED HERE';
$dbDatabase = $dbUsername . '__dewikinews_rss';

// Database configuration for direct DB access, which is needed for category
// RSS feeds (only used by cat*.php)
$dbHostDewikinews = 'dewikinews.labsdb';
$dbDatabaseDewikinews = 'dewikinews_p';

?>
