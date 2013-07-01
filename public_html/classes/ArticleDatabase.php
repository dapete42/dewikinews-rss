<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('Database.php');

class ArticleDatabase {

  protected $db;
  protected $prefix;

  public function __construct(Database $db, $prefix = '') {
    $this->db = $db;
    $this->prefix = $prefix;
  }

  protected function tableName($table) {
    return $this->prefix . $table;
  }

  public function loadArticles($limit = 0) /* array(), Article[] */  {
    $articles = array();
    $this->db->connect();
    $table = $this->tableName('articles');
    $tableCat = $this->tableName('categories');

    $sql = "SELECT title, text, id, revid, touched, published FROM $table ORDER BY published DESC";
    if ($limit) {
      $sql .= " LIMIT " .  $this->db->escape($limit);
    }

    if ($q = $this->db->query($sql)) {
      while ($row = $this->db->fetch_array($q)) {
        $categories = array();

        $sql = "SELECT category FROM $tableCat WHERE id=" . $this->db->escape($row['id']);
        if ($qcat = $this->db->query($sql)) {
          while ($rowcat = $this->db->fetch_array($qcat)) {
            $categories[] = $rowcat['category'];
          }
        }

        $article = new Article($row['title'], $row['text'], $row['id'], $row['revid'], new Timestamp($row['touched']), new Timestamp($row['published']), $categories);
        $articles[$row['id']] = $article;
	mysql_free_result($qcat);
      }
    }

    return $articles;
  }

  public function deleteArticles ($articles = array() /* Article[] */) {
    $this->db->connect();
    $table = $this->tableName('articles');
    $tableCat = $this->tableName('categories');

    foreach ($articles as $article) {
      $id = $this->db->escape($article->getID());
      $sql = "DELETE FROM $table WHERE id=$id";
      $this->db->query($sql);
      $sql = "DELETE FROM $tableCat WHERE id=$id";
      $this->db->query($sql);
    }
  }

  public function saveArticles($articles = array() /* Article[] */) {
    $this->db->connect();
    $table = $this->tableName('articles');
    $tableCat = $this->tableName('categories');

    foreach ($articles as $article) {
      $db_title     = $this->db->escapeString($article->getTitle());
      $db_text      = $this->db->escapeString($article->getText());
      $db_id        = $this->db->escape($article->getID());
      $db_revid     = $this->db->escape($article->getRevID());
      $db_touched   = $this->db->escapeString($article->getTouched()->getTimestamp());
      $db_published = $this->db->escapeString($article->getPublished()->getTimestamp());

      $sql = "INSERT INTO $table (title,text,id,revid,touched,published) VALUES ($db_title,$db_text,$db_id,$db_revid,$db_touched,$db_published)";
      $this->db->query($sql);

      $categories = $article->getCategories();
      $inserts = array();
      foreach (@$categories as $category) {
        $db_category = $this->db->escapeString($category);
        $inserts[] = "($db_category, $db_id)";
      }
      if (count($inserts) > 0) {
        $sql = "INSERT INTO $tableCat (category,id) VALUES " . implode (',', $inserts);
        $this->db->query($sql);
      }
    }
  }

}

?>
