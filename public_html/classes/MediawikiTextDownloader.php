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

class MediawikiTextDownloader extends Downloader {

  protected $urlDomain;
  protected $urlPath;
  protected $urlScript;

  public function __construct($domain = 'en.wikipedia.org', $path = 'w', $script = 'index.php') {
    $this->urlDomain = $domain;
    $this->urlPath = $path;
    $this->urlScript = $script;
  }

  public function download(Article $article) /* boolean */ {
    $titleConverter = new MediawikiTitleConverter();
    $urlTitle = $titleConverter->convertedText($article->getTitle());
    $url = 'http://' . $this->urlDomain . '/' . $this->urlPath . '/' . $this->urlScript . '?title=' . $urlTitle . '&action=raw';

    // Download with CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MediawikiTextDownloader (German Wikinews RSS feed; +http://de.wikinews.org/wiki/Benutzer:Dapete/RSS)');

    $text = curl_exec($ch);

    curl_close($ch);

    if ($text != $article->getText()) {
      $article->setText($text);
      return true;
    }
    else {
      return false;
    }
  }

}

?>
