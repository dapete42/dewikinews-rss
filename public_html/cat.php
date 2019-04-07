<?php

// RSS Feed for German Wikinews - simple by category
// (c) 2005-2013 Peter Schloemer <dapete.ml@googlemail.com>
//
// This program comes a) without any warranty and b) without any restrictions
// to use the source code in your own programs, provided you credit Peter
// Schloemer.
 

// Initialization (DB etc.)
include ('../php/init.php');

// Shared functions
include ('../php/cat-functions.php');

// Output funcitons
include ('../php/cat-output.php');

// Get parameter
$cat = get_cat();
// Convert to DB format
$cat = str_replace(' ','_',$cat);
$nice_cat = str_replace('_',' ',$cat);

// New RSS writer with Wikinews "branding"

$channel = array();

$channel['title'] = 'Wikinews-Artikel in Kategorie:' . $nice_cat;
$channel['description'] = '';
$channel['link'] = 'http://de.wikinews.org/wiki/Benutzer:Dapete/RSS';

$channel['pubDate'] = strftime("%a, %d %b %Y %H:%M:%S %z");

// Open database
@mysqli_connect($dbHostDewikinews,$dbUsername,$dbPassword) or die ("Unable to connect to mysql server: $dbUsername@$dbHostDewikinews");
db_select ($dbDatabaseDewikinews);

$result = mysqli::query('SELECT /* LIMIT:60 NM */ page_id, page_title, page_touched, cl2.cl_timestamp FROM categorylinks cl1 INNER JOIN page ON cl1.cl_from=page_id INNER JOIN categorylinks cl2 ON cl2.cl_from=page_id WHERE cl2.cl_to="Veröffentlicht" AND cl1.cl_to="' . mysql_real_escape_string($cat) . '" AND page_namespace=0 ORDER BY cl_timestamp DESC LIMIT 100;');

if (! $result) {
  die ("Error looking up items.\n".mysql_error());
}

$items = array();

// Now cycle through all lines
while ($row = mysql_fetch_array($result)) {
  // Item data
  $item = array();
  $item['guid'] = $row['page_id'];
  $item['categories'] = array();

  $item['title'] = db_to_title($row['page_title']);
  $item['link'] = 'http://de.wikinews.org/wiki/'.db_to_wiki($row['page_title']);
  $item['pubDate'] = datetime_to_rfc822($row['cl_timestamp']);

  $items[$row['page_id']] = $item;
}

// Output result
  rss2_render($channel, $items);

?>
