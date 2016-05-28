<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('Downloader.php');
include_once('MediawikiTitleConverter.php');

class MediawikiQueryCategoryDownloader extends Downloader {

  protected $urlDomain;
  protected $urlPath;
  protected $useHttps;

  public function __construct($domain = 'en.wikipedia.org', $path = 'w', $useHttps = false) {
    $this->urlDomain = $domain;
    $this->urlPath = $path;
    $this->useHttps = $useHttps;
  }

  public function download(Article $article) /* boolean */ {
    $titleConverter = new MediawikiTitleConverter();
    $urlTitle = $titleConverter->convertedText($article->getTitle());

    $url = ($this->useHttps ? 'https': 'http') . '://' . $this->urlDomain . '/' . $this->urlPath . "/api.php?format=php&action=query&titles=$urlTitle&prop=categories";

    // Download with CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MediawikiQueryCategoryDownloader (German Wikinews RSS feed; +https://de.wikinews.org/wiki/Benutzer:Dapete/RSS)');
    $data = curl_exec($ch);
    curl_close($ch);

    // Regain the serialized array data
    $yurik = unserialize($data);

    $categories = array();

    // Loop through all pages
    foreach (@$yurik['query']['pages'] as $page) {
      if (@$page['categories']) {
        foreach ($page['categories'] as $cat) {
            $categories[] = preg_replace('/^.*?:/', '', $cat['title']);
        }
      }
    }

    $article->setCategories($categories);
  }

}

?>
