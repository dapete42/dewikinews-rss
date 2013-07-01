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

class DEWikinewsTeXConverter extends Converter {

  public function convertedText($text) {
    // German Wikinews special - remove place/date intro, if no template was
    // used for that
    $ret = preg_replace('/.*?, \d\d\.\d\d\.\d\d\d\d (–|-|&ndash;) /', '', $text);

    // Only keep the text up to the first heading
    $split = split('==Quellen', $ret);
    $ret = $split[0];
    $split = split('== Quellen', $ret);
    $ret = $split[0];
    $split = split('==Weblinks', $ret);
    $ret = $split[0];
    $split = split('== Weblinks', $ret);
    $ret = $split[0];

    // Convert to Text
    $textConverter = new TextConverter();
    $ret = $textConverter->convertedText($ret, 0);

    // Cut text
    $ret = $this->cutText($ret, $this->cut);

    // Now make replacements needet for TeX

    $conv = array (
      ' ' => '\\ ',
      ',,' => '\\,\\,',
      '„' => ',,',
      '“' => '``',

      '‚' => ',',
      '‘' => '`',

      '–' => '--',
      '…' => '\dots{}',

      'Ä' => '"A',
      'Ö' => '"O',
      'Ü' => '"U',
      'ä' => '"a',
      'ö' => '"o',
      'ü' => '"u',

      'ß' => '"s',

      'Á' => '\\\'A',
      'É' => '\\\'E',
      'Í' => '\\\'I',
      'Ó' => '\\\'O',
      'Ú' => '\\\'U',
      'á' => '\\\'a',
      'é' => '\\\'e',
      'í' => '\\\'i',
      'ó' => '\\\'o',
      'ú' => '\\\'u',

      'À' => '\\`A',
      'È' => '\\`E',
      'Ì' => '\\`I',
      'Ò' => '\\`O',
      'Ù' => '\\`U',
      'à' => '\\`a',
      'è' => '\\`e',
      'ì' => '\\`i',
      'ò' => '\\`o',
      'ù' => '\\`u',

      'Â' => '\\^A',
      'Ê' => '\\^E',
      'Î' => '\\^I',
      'Ô' => '\\^O',
      'Û' => '\\^U',
      'â' => '\\^a',
      'ê' => '\\^e',
      'î' => '\\^i',
      'ô' => '\\^o',
      'û' => '\\^u',

      'Ā' => '\\=A',
      'Ē' => '\\=E',
      'Ī' => '\\=I',
      'Ō' => '\\=O',
      'Ū' => '\\=U',
      'ā' => '\\=a',
      'ē' => '\\=e',
      'ī' => '\\=i',
      'ō' => '\\=o',
      'ū' => '\\=u'
    );

    // Æ  æ Œ œ Å å Ø ø Ç ç Ñ ñ € · ′ – ‚‘

    // Mask special characters
    $ret = addcslashes($ret, '\$&%#_{}[]^"\'');

    // Now convert Unicode stuff to TeX
    $ret = str_replace(array_keys($conv), array_values($conv), $ret);

    // Headings haven't been handled up to here. Do that now
//    $ret = preg_replace ('/=== *(.*?) *===', "\n\n\\subsubsection*{$1}\n\n", $ret);
//    $ret = preg_replace ('/== *(.*?) *==', "\n\n\\subsection*{$1}\n\n", $ret);

    return $ret;
  }

}

?>