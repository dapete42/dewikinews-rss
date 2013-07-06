<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

class Timestamp {

  protected $timestamp /* string */;

  public function __construct($timestamp) {
    $this->timestamp = $timestamp;
  }

  public static function fromDB($datetime) {
    $year = substr($datetime, 0, 4);
    $month = substr($datetime, 5, 2);
    $day = substr($datetime, 8, 2);
    $hour = substr($datetime, 11, 2);
    $minute = substr($datetime, 14, 2);
    $second = substr($datetime, 17, 2);
    return new Timestamp("$year$month$day$hour$minute$second");
  }

  public static function fromAPI($datetime) {
    return Timestamp::fromDB($datetime);
  }

  public function getTimestamp() /* string */ {
    return $this->timestamp;
  }

  public function getUNIXTimestamp() /* string */ {
    $year = substr($this->timestamp, 0, 4);
    $month = substr($this->timestamp, 4, 2);
    $day = substr($this->timestamp, 6, 2);
    $hour = substr($this->timestamp, 8, 2);
    $minute = substr($this->timestamp, 10, 2);
    $second = substr($this->timestamp, 12, 2);
    return mktime($hour, $minute, $second, $month, $day, $year);
  }

  public function getRFC822String() /* string */ {
    return strftime('%a, %d %b %Y %H:%M:%S %z', $this->getUNIXTimestamp());
  }

  public function getLocaleDate($locale = 'en', $format = '%x') /* string */ {
    setlocale (LC_TIME, 'de_DE');
    return strftime($format, $this->getUNIXTimestamp());
  }

}

?>