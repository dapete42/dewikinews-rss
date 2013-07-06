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

class MediawikiTitleConverter extends Converter {

  public function convertedText($text) {
    // Spaces become underscores (so this function can be used either on
    // titles in the database or 'pure' titles)
    $ret = str_replace(' ', '_', $text);
    // URL-encode now
    $ret = urlencode($ret);
    // '/' may not be encoded
    $ret = str_replace('%2F', '/', $ret);
    // ':' doesn't have to be
    $ret = str_replace('%3A', ':', $ret);
    return $ret;
  }

}

?>