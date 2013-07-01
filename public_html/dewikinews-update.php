<?php

include('init.php');

include_once('classes/ArticleDatabase.php');
include_once('classes/MediawikiQueryCategoryMultiDownloader.php');
include_once('classes/MediawikiQueryCategoryDownloader.php');
include_once('classes/MediawikiTextDownloader.php');
include_once('classes/MySQLDatabase.php');

$multi = new MediawikiQueryCategoryMultiDownloader('de.wikinews.org');

$articlesNew = $multi->download(50, 'Veröffentlicht');

$db = new MySQLDatabase($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$adb = new ArticleDatabase($db, '');

if (!$db->connect()) {
  print gmdate('Y-m-d H:i:s') . " Cannot connect to database.\n";
  die ();
}

$articlesOld = $adb->loadArticles();

$dlText = new MediawikiTextDownloader('de.wikinews.org');
$dlCat = new MediawikiQueryCategoryDownloader('de.wikinews.org');

$articlesDelete = array();
$articlesSave = array();

foreach ($articlesNew as $id => $article) {
  if (@$articlesOld[$id]) {
    if ($articlesOld[$id]->getRevID() != $article->getRevID()) {
      print gmdate('Y-m-d H:i:s') . " $id: changed, downloading text and categories\n";
      $dlText->download($article);
      $dlCat->download($article);
      $articlesDelete[$id] = $article;
      $articlesSave[$id] = $article;
    }
  }
  else {
    print gmdate('Y-m-d H:i:s') . " $id: new, downloading text and categories\n";
    $dlText->download($article);
    $dlCat->download($article);
    $articlesSave[$id] = $article;
  }
}

foreach ($articlesOld as $id => $article) {
  if (!@$articlesNew[$id]) {
    print gmdate('Y-m-d H:i:s') . " $id: old, marking for deletion\n";
    $articlesDelete[$id] = $article;
  }
}

$adb->deleteArticles($articlesDelete);
$adb->saveArticles($articlesSave);

?>
