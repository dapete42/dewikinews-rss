<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

// Provides initialization and some db config variables
require('../php/init.php');

require('../php/classes/ArticleDatabase.php');
require('../php/classes/DEWikinewsConverter.php');
require('../php/classes/DEWikinewsTeXConverter.php');
require('../php/classes/HTMLOutput.php');
require('../php/classes/MySQLDatabase.php');
require('../php/classes/NoConverter.php');
require('../php/classes/RSSOutput.php');
require('../php/classes/TeXOutput.php');
require('../php/classes/TextOutput.php');
require('../php/classes/WMLOutput.php');

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
  $baseurl = 'https://de.wikinews.org/wiki/';
  $infourl = 'https://de.wikinews.org/wiki/Benutzer:Dapete/RSS';
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
  die ("Can't connect to database.");
}

$articles = $adb->loadArticles($count);
$out->outputArticles($articles);

?>
