<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('MultiDownloader.php');

class MediawikiQueryCategoryMultiDownloader extends MultiDownloader {

  protected $urlDomain;
  protected $urlPath;

  public function __construct($domain = 'en.wikipedia.org', $path = 'w') {
    $this->urlDomain = $domain;
    $this->urlPath = $path;
  }

  public function download($count = 20, $category = '') /* Article[] */ {
    $titleConverter = new MediawikiTitleConverter();


    $articles = array();

    $url = 'http://' . $this->urlDomain . '/' . $this->urlPath . "/api.php?format=php&action=query&list=categorymembers&cmprop=ids|title|timestamp&cmlimit=$count&cmnamespace=0&cmsort=timestamp&cmdir=desc&cmtitle=Category:" . urlencode($category);

    // Download with CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MediawikiQueryCategoryMultiDownloader (German Wikinews RSS feed; +http://de.wikinews.org/wiki/Benutzer:Dapete/RSS)');
    $data = curl_exec($ch);
    curl_close($ch);

    // Regain the serialized array data
    $yurik = unserialize($data);

    // Loop through all pages
    if (count(@$yurik['query']['categorymembers']) > 0) {
      foreach ($yurik['query']['categorymembers'] as $page) {
        $key = $page['pageid'];
        $published = Timestamp::fromAPI($page['timestamp']);
        $touched = $published;
        $article = new Article($page['title'], '', $key, $key, $touched, $published, array());

        // Add to return array
        $articles[$key] = $article;
      }
    }

    return $articles;
  }
}

?>
