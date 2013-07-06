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
include_once('HTMLEntityConverter.php');

class TextConverter extends Converter {

  public function convertedText($text) {
    // Filter HTML Entities
    $htmlEntityConverter = new HTMLEntityConverter();
    $ret = $htmlEntityConverter->convertedText($text, 0);

    // Do the actual Wiki conversion stuff
    $ret = $this->wiki2txt($ret);

    // Special stuff can be removed
    $ret = str_replace('__NOTOC__', '', $ret);
    $ret = str_replace('__TOC__', '', $ret);

    // Remove possible remaining HTML tags
    $ret = preg_replace('/<.*?>/', '', $ret);

    // Return cut text
    return $this->cutText($ret, $this->cut);
  }

  // Convert $text with MediaWiki wikitext to a plain text. Ignores all HTML
  // stuff, including entities

  private function wiki2txt ($text) {
    $pos = 0;
    return trim($this->w2t($text,$pos,array()));
  }

  // $text
  //   Text to be processed
  // $pos
  //   Current position in the text
  // $abort
  //   Array of Strings to terminate when encountering
  //
  // Returns the parsed output

  private function w2t (&$text, &$pos, $abort) {
    // Output string
    $out = '';

    // Check for things to parse until we encounter an abort condition or reach
    // the end of the text.
    while (!$this->abort($text,$pos,$abort) && $pos<strlen($text)) {
      // Images
      if (substr($text,$pos,8) == '[[Image:' || substr($text,$pos,7) == '[[Bild:' || substr($text,$pos,7) == '[[File:' || substr($text,$pos,8) == '[[Datei:') {
        if (substr($text,$pos,7) == '[[Bild:' || substr($text,$pos,7) == '[[File:') {
          $pos += 7;
        }
        else {
          $pos += 8;
        }
        // Get first part of the link
        $this->w2t($text,$pos,array('|',']]'));
        // While there are more parts, go on
        while (substr($text,$pos,1) == '|') {
          $pos++;
          $this->w2t($text,$pos,array('|',']]'));
        }
        $pos += 2;
      }
      // Categories
      else if (substr($text,$pos,11) == '[[Category:' || substr($text,$pos,12) == '[[Kategorie:') {
        if (substr($text,$pos,12) == '[[Kategorie:')
          $pos += 12;
        else
          $pos += 11;
        // Get first part of the link
        $this->w2t($text,$pos,array('|',']]'));
        // While there are more parts, go on
        while (substr($text,$pos,1) == '|') {
          $pos++;
          $this->w2t($text,$pos,array('|',']]'));
        }
        $pos += 2;
      }
      // Internal link
      else if (substr($text,$pos,2) == '[[') {
        $pos += 2;
        // Get first part of the link
        $link = $this->w2t($text,$pos,array('|',']]'));
        // While there are more parts, go on
        $label = '';
        while (substr($text,$pos,1) == '|') {
          $pos++;
          $label = $this->w2t($text,$pos,array('|',']]'));
        }
        // In the end, add either the link or the label
        if ($label)
          $out .= $label;
        else
          $out .= $link;
        $pos += 2;
      }
      // External link
      else if (substr($text,$pos,1) == '[') {
        $pos++;
        // Get first part of the link
        $link = $this->w2t($text,$pos,array(' ',']'));
        // While there are more parts, go on
        $label = '';
        if (substr($text,$pos,1) == ' ') {
          $pos++;
          $label = $this->w2t($text,$pos,array(']'));
        }
        //  In the end, add either the link or the label
        if ($label)
          $out .= $label;
        else
          $out .= $link;
        $pos++;
      }
      // Template
      else if (substr($text,$pos,2) == '{{') {
        $pos += 2;
        $this->w2t($text,$pos,array('}}'));
        $pos += 2;
      }
      // Table
      else if (substr($text,$pos,2) == '{|') {
        $pos += 2;
        $this->w2t($text,$pos,array('|}'));
        $pos += 2;
      }
      // Comment
      else if (substr($text,$pos,4) == '<!--') {
        $pos += 4;
        $this->w2t($text,$pos,array('-->'));
        $pos += 3;
      }
      // Bold
      else if (substr($text,$pos,3) == '\'\'\'') {
        $pos += 3;
        $out .= $this->w2t($text,$pos,array('\'\'\''));
        $pos += 3;
      }
      // Italics
      else if (substr($text,$pos,2) == '\'\'') {
        $pos += 2;
        $out .= $this->w2t($text,$pos,array('\'\''));
        $pos += 2;
      }
      // REF
      else if (substr($text,$pos,6) == '<ref/>') {
	$pos += 6;
      }
      else if (substr($text,$pos,7) == '<ref />') {
        $pos += 7;
      }
      else if (substr($text,$pos,4) == '<ref') {
        $pos += 4;
        $this->w2t($text,$pos,array('</ref>'));
	$pos += 6;
      }
      // Nowiki
      else if (substr($text,$pos,8) == '<nowiki>') {
        $pos += 8;
        while (substr($text,$pos,9) != '</nowiki>' && $pos<strlen($text)) {
          $out .= substr($text,$pos,1);
          $pos++;
        }
        $pos += 9;
      }
      else {
        $out .= substr($text,$pos,1);
        $pos++;
      }
    }
    return $out;
  }

  // Does String $text, starting at position $pos, begin with a string in
  // array $search?
  private function abort (&$text, $pos, $search) {
    while (list ($k, $v) = each($search))
    {
      if (substr($text,$pos,strlen($v)) == $v) {
        return true;
      }
    }
    return false;
  }

}

?>
