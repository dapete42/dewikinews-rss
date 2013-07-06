<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('Converter.php');
include_once('TextConverter.php');

abstract class Output {

  protected $converter; /* Converter */
  protected $title;
  protected $description;
  protected $link;
  protected $baseURL;

  public function __construct(Converter $converter = NULL, $title = '', $description = '', $link = '', $baseURL = '') {
    if ($converter) {
      $this->converter = $converter;
    }
    else {
      $this->converter = new TextConverter();
    }
    $this->title = $title;
    $this->description = $description;
    $this->link = $link;
    $this->baseURL = $baseURL;
  }

  public function outputArticles($articles = array(), $count = 100) {
    header('Content-type: ' . $this->contentType() . '; charset=' . $this->contentEncoding());
    $this->outputStart($articles);

    $i = 1;
    if ($articles) {
      foreach ($articles as $article) {
        if ($i>$count) {
          break;
        }
        $this->outputArticle($article, $articles);
        $i++;
      }
    }

    $this->outputEnd($articles);
  }

  protected function contentType() {
    return "text/plain";
  }

  protected function contentEncoding() {
    return "utf-8";
  }

  protected abstract function outputStart($articles);

  protected abstract function outputEnd($articles);

  protected abstract function outputArticle(Article $article, $articles);

}

?>
