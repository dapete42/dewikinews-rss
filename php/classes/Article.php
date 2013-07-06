<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Timestamp.php');

function sort_Article_published(Article $a, Article $b) {
  $pubA = $a->getPublished()->getTimestamp();
  $pubB = $b->getPublished()->getTimestamp();
  if ($pubA > $pubB) {
    return -1;
  }
  elseif ($pubA < $pubB) {
    return 1;
  }
  else {
    return 0;
  }
}

class Article {

  protected $title;
  protected $text;
  protected $id;
  protected $revID;
  protected $touched; /* Timestamp */
  protected $published; /* Timestamp */
  protected $categories;

  public function __construct($title, $text = '', $id = 0, $revID = 0, Timestamp $touched = NULL, Timestamp $published = NULL, $categories = array()) {
    $this->title = $title;
    $this->text = $text;
    $this->id = $id;
    $this->revID = $revID;
    if ($touched) {
      $this->touched = $touched;
    }
    else {
      $this->touched = new Timestamp("19700101000000");
    }
    if ($published) {
      $this->published = $published;
    }
    else {
      $this->published = new Timestamp("19700101000000");
    }
    $hiss->categories = $categories;
  }

  public function getText() {
    return $this->text;
  }

  public function setText($text) {
    $this->text = $text;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getID() {
    return $this->id;
  }

  public function setID($id) {
    $this->id = $id;
  }

  public function getRevID() {
    return $this->revID;
  }

  public function setRevID($revID) {
    $this->revID = $revID;
  }
  public function getTouched() /* Timestamp */ {
    return $this->touched;
  }

  public function setTouched(Timestamp $touched) {
    $this->touched = $touched;
  }

  public function getPublished() /* Timestamp */ {
    return $this->published;
  }

  public function setPublished(Timestamp $published) {
    $this->published = $published;
  }

  public function getCategories() /* array(), string[] */ {
    return $this->categories;
  }

  public function setCategories($categories = array()) {
    $this->categories = $categories;
  }

}

?>