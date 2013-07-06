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

class NoConverter extends Converter {

  public function convertedText($text) {
    return $text;
  }

}

?>
