<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Database.php');

class MySQLDatabase extends Database {

  protected function connectBase() {
    $this->dbResource = mysqli_connect($this->dbHost, $this->dbUsername, $this->dbPassword);
    if ($this->dbResource) {
      mysqli_select_db($this->dbResource, $this->database);
    }
  }

  protected function disconnectBase() {
    if ($this->dbResource) {
      mysqli_close($this->dbResource);
    }
  }

  public function query ($sql = '') {
    if ($this->connected) {
      return mysqli_query($this->dbResource, $sql);
    }
    else {
      return false;
    }
  }

  public function fetch_array($result) {
    if ($this->connected) {
      return mysqli_fetch_array($result);
    }
    else {
      return false;
    }
  }

   public function escape ($string) {
     return addcslashes($string, "\x00\n\r'\"\x1a\\");
   }

}

?>
