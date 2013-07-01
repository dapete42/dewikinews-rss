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

class RSSOutput extends Output {

  protected function contentType() {
    return "text/xml";
  }

  protected function outputStart($articles) {
    # Preamble
    print "<?xml version=\"1.0\"?>\n";
    # This adds a stylesheet so the feed can be viewn directly in a browser.
    print "<?xml-stylesheet href=\"rss.css\" type=\"text/css\"?>\n";
    print "<rss version=\"2.0\">\n";
    print "<channel>\n";

    # Start with channel output

    print '<title>' . htmlspecialchars($this->title) . "</title>\n";
    print '<description>' . htmlspecialchars($this->description) . "</description>\n";
    print '<link>' . htmlspecialchars($this->link) . "</link>\n";
  }

  protected function outputEnd($articles) {
    # Postamble
    print "</channel>\n";
    print "</rss>\n";
  }

  protected function outputArticle(Article $article, $articles) {
    $conv = new MediawikiTitleConverter();

    print "<item>\n";

    print '<title>' . htmlspecialchars($article->getTitle()) . "</title>\n";
    print '<link>' . htmlspecialchars($this->baseURL) . $conv->convertedText($article->getTitle()) . "</link>\n";
    print '<guid isPermaLink="false">' . htmlspecialchars($article->getID()) . "</guid>\n";
    print '<pubDate>' . htmlspecialchars($article->getPublished()->getRFC822String()) . "</pubDate>\n";

    print '<description>' . htmlspecialchars($this->converter->convertedText($article->getText())) . "</description>\n";

    print "</item>\n";
  }

}

?>
