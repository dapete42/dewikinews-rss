<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('Output.php');

class TextOutput extends Output {

  protected function contentType() {
    return "text/plain";
  }

  protected function outputStart($articles) {
    if ($this->title) {
      print $this->title . "\n\n";
    }
    if ($this->description) {
      print $this->description . "\n\n";
    }
    if ($this->link) {
      print $this->link . "\n\n";
    }
  }

  protected function outputEnd($articles) {
  }

  protected function outputArticle(Article $article, $articles) {
    print $article->getTitle() . "\n\n";
    print $this->converter->convertedText($article->getText()) . "\n\n";
  }

}

?>