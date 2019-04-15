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

class DEWikinewsConverter extends Converter {

  public function convertedText($text) {
    // German Wikinews special - remove place/date intro, if no template was
    // used for that
    $ret = preg_replace('/.*?, \d\d\.\d\d\.\d\d\d\d (–|-|&ndash;) /', '', $text);

    // Only keep the text up to the first heading
    $split = explode('==', $ret);
    $ret = $split[0];

    // Convert to Text
    $textConverter = new TextConverter();
    $ret = $textConverter->convertedText($ret, 0);

    // Convert multiple white spaces to one; makes everything one line
    $ret = preg_replace('/\s+/', ' ', $ret);

    // Return cut text
    return $this->cutText($ret, $this->cut);
  }

}

?>
