<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Converter.php');

/*
  Class NullConverter 	 

  A converter with the simplest possible implementation - it always returns an
  empty string.
*/

class NullConverter extends Converter {

  public function convertedText($text) {
    return '';
  }

}

?>
