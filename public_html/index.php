<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

// Provides initialization and some db config variables
require('init.php');

require('classes/ArticleDatabase.php');
require('classes/DEWikinewsConverter.php');
require('classes/DEWikinewsTeXConverter.php');
require('classes/HTMLOutput.php');
require('classes/MySQLDatabase.php');
require('classes/NoConverter.php');
require('classes/RSSOutput.php');
require('classes/TeXOutput.php');
require('classes/TextOutput.php');
require('classes/WMLOutput.php');

$count = @$_GET['count'];
$cut = @$_GET['cut'];
$format = @$_GET['format'];
$raw = @$_GET['raw'];
$ssl = @$_GET['ssl'];

if ($count=='') {
  $count = 20;
}
else {
  $count = max(0,intval($count));
}

if ($cut=='') {
  if ($format=='wml') {
    $cut = 500;
  }
  elseif ($format=='tex') {
    $cut = 0;
  }
  else {
// temporarily set to 500, too
    $cut = 500;
  }
}
else {
  $cut = max(0,intval($cut));
}

if ($format=='tex') {
  $conv = new DEWikinewsTeXConverter($cut);
}
else {
  if ($raw) {
    $conv = new NoConverter($cut);
  }
  else {
    $conv = new DEWikinewsConverter($cut);
  }
}

if ($ssl) {
  $baseurl = 'https://secure.wikimedia.org/wikinews/de/wiki/';
  $infourl = 'https://secure.wikimedia.org/wikinews/de/wiki/Benutzer:Dapete/RSS';
}
else {
  $baseurl = 'http://de.wikinews.org/wiki/';
  $infourl = 'http://de.wikinews.org/wiki/Benutzer:Dapete/RSS';
}

if ($format=='html') {
  $out = new HTMLOutput($conv, 'Wikinews RSS-Feed', 'RSS-Feed für die deutschsprachigen Wikinews. Inhalte stehen unter der CC-BY-2.5-Lizenz (http://creativecommons.org/licenses/by/2.5/).', $infourl, $baseurl);
}
elseif ($format=='tex') {
  $out = new TeXOutput($conv, 'Wikinews RSS-Feed', 'RSS-Feed für die deutschsprachigen Wikinews. Inhalte stehen unter der CC-BY-2.5-Lizenz (http://creativecommons.org/licenses/by/2.5/).', $infourl, $baseurl);
}
elseif ($format=='text') {
  $out = new TextOutput($conv, 'Wikinews RSS-Feed', 'RSS-Feed für die deutschsprachigen Wikinews. Inhalte stehen unter der CC-BY-2.5-Lizenz (http://creativecommons.org/licenses/by/2.5/).', $infourl, $baseurl);
}
elseif ($format=='wml') {
  $out = new WMLOutput($conv, 'Wikinews RSS-Feed', 'RSS-Feed für die deutschsprachigen Wikinews. Inhalte stehen unter der CC-BY-2.5-Lizenz (http://creativecommons.org/licenses/by/2.5/).', $infourl, $baseurl);
}
else {
  $out = new RSSOutput($conv, 'Wikinews RSS-Feed', 'RSS-Feed für die deutschsprachigen Wikinews. Inhalte stehen unter der CC-BY-2.5-Lizenz (http://creativecommons.org/licenses/by/2.5/).', $infourl, $baseurl);
}

$db = new MySQLDatabase($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$adb = new ArticleDatabase($db, '');

if (!$db->connect()) {
  die ("dewikinews.php: Can't connect to database.");
}

$articles = $adb->loadArticles($count);
$out->outputArticles($articles);

?>
