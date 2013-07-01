<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');

abstract class Converter {

  protected $cut;

  public function __construct($cut = 0) {
    $this->cut = $cut;
  }

  abstract function convertedText($text);

  protected function cutText($text, $cut = 0) /* string */ {
     if (($cut > 0) && (strlen($text)>$cut)) {
      // Finally, cut text to specified number of chars.
       preg_match('/(.{1,'.$cut.'})(\s|$)/m', $text, $results);
       return $results[0] . '…';
     }
     else {
       return $text;
     }
  }

}

?>
