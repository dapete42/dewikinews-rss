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

class HTMLOutput extends Output {

/*
  private $useUTF8 = true;

  public function getUseUTF8() {
    return $this->useUTF8;
  }

  public function setUseUTF8($value = true) {
    $this->useUTF8 = $value;
    print $this->useUTF8;
  }

  protected function contentEncoding() {
    if ($this->useUTF8) {
      return 'utf-8';
    }
    else {
      return 'iso-8859-1';
    }
  }
*/

  protected function contentType() {
    return 'text/html';
  }

  protected function outputStart($articles) {
    # Preamble
    print "<?xml version=\"1.0\"?>\n";
    # This adds a stylesheet so the feed can be viewn directly in a browser.
    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
    print "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
    print "<head>\n";
    print '<title>' . $this->htmlEncode($this->title) . "</title>\n";
    print "</head>\n";
    print "<body>\n";
    print '<h1><a href="' . $this->htmlEncode($this->link) . '">' . $this->htmlEncode($this->title) . "</a></h1>\n";
    print '<p>' . $this->htmlEncode($this->description) . "</p>\n";
  }

  protected function outputEnd($articles) {
    # Postamble
    print "</body>\n";
    print "</html>\n";
  }

  protected function outputArticle(Article $article, $articles) {
    $conv = new MediawikiTitleConverter();
    print '<h2><a href="' . $this->htmlEncode($this->baseURL) . $conv->convertedText($article->getTitle()) . '">' . $this->htmlEncode($article->getTitle()) . "</a></h2>\n";
    print '<p>' . $this->htmlEncode($this->converter->convertedText($article->getText())) . "</p>\n";
  }

  protected function htmlEncode($string) {
//    if ($this->useUTF8) {
      return htmlspecialchars($string);
//    }
//    else {
//      return htmlspecialchars(mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8'));
//    }
  }

}

?>
