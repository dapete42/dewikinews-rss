<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('MediawikiTitleConverter.php');
include_once('Output.php');
include_once('Timestamp.php');

class WMLOutput extends Output {

  protected function contentType() {
    return "text/vnd.wap.wml";
  }

  protected function outputStart($articles) {
    # Preamble
    print "<?xml version=\"1.0\"?>\n";
    # This adds a stylesheet so the feed can be viewn directly in a browser.
    print "<!DOCTYPE wml PUBLIC \"-//WAPFORUM/DTD WML 1.1//EN\" \"http://www.wapforum.
org/DTD/wml_1.1.xml\">\n";
    print "<wml>\n";
    print '<card id="main" title="' . htmlspecialchars($this->title) . "\">\n";
    print '<p>' . htmlspecialchars($this->description) . "</p>\n";
    if ($articles) {
      foreach ($articles as $article) {
        print '<p><a href="#a' . htmlspecialchars($article->getID()) . '">' . htmlspecialchars($article->getTitle()) . "</a></p>\n";
      }
    }
    print "</card>\n";
  }

  protected function outputEnd($articles) {
    # Postamble
    print "</wml>\n";
  }

  protected function outputArticle(Article $article, $articles) {
    $conv = new MediawikiTitleConverter();
    print '<card id="a' . htmlspecialchars($article->getID()) . '" title="' . htmlspecialchars($article->getTitle()) . '">' . "\n";
    print '<p>' . htmlspecialchars($this->converter->convertedText($article->getText())) . "</p>\n";
    print '<p><a href="' . htmlspecialchars($this->baseURL) . $conv->convertedText($article->getTitle()) . '">' . htmlspecialchars($article->getTitle()) . "</a></p>\n";
    print "</card>\n";
  }

}

?>
