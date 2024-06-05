<?php

global $db;
$db = new PDO('mysql:host=' . conf('db_host') . ';dbname=' . conf('db_name'), conf('db_user'), conf('db_psw'),
  array(PDO::MYSQL_ATTR_FOUND_ROWS => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

function db_row($stmt) {
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_error() {
  global $db;
  return $db->errorInfo();
}

function db_query($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  if ($res) {
    while ($row = db_row($res)) {
      if (isset($row['id']) && !isset($r[$row['id']])) {
        $r[$row['id']] = $row;
      }
      else {
        $r[] = $row;
      }
    }
  }
  return $r;
}

function db_result($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  if ($res) {
    if ($row = db_row($res)) {
      return $row[0];
    }
    else {
      return FALSE;
    }
  }
  else {
    return FALSE;
  }
}

function db_command($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  return $res = $q->execute($args);
}

function db_insert_id() {
  global $db;
  return $db->lastInsertId();
}

function db_get($name, $default = FALSE) {
  if (strlen($name) == 0) {
    return $default;
  }
  $value = db_result("SELECT value FROM variable WHERE name = ?", $name);
  if ($value === FALSE) {
    return $default;
  }
  else {
    return $value;
  }
}

function db_set($name, $value) {
  if (strlen($name) == 0) {
    return;
  }

  $v = db_get($name);
  if ($v === FALSE) {
    $q = "INSERT INTO variable VALUES (?, ?)";
    return db_command($q, $name, $value) > 0;
  }
  else {
    $q = "UPDATE variable SET value = ? WHERE name = ?";
    return db_command($q, $value, $name) > 0;
  }
}

function db_sort_sql() {
}

function db_pager_query() {
}

function db_array() {
  global $db;
  $args = func_get_args();
  $key = array_shift($args);
  $query = array_shift($args);
  $q = $db->prepare($query);
  $res = $q->execute($args);
  $r = array();
  if ($res) {
    while ($row = db_row($res)) {
      if (!empty($key) && isset($row[$key]) && !isset($r[$row[$key]])) {
        $r[$row[$key]] = $row;
      }
      else {
        $r[] = $row;
      }
    }
  }
  return $r;
}

function write_new_user($login, $password_hash) {
  global $db;
  try {
      $stmt = $db->prepare("INSERT INTO users (login, password_hash) VALUES (:login, :password_hash)");
      $stmt->bindParam('login', $login);
      $stmt->bindParam('password_hash', $password_hash);
      $stmt->execute();

      $user_id = $db->lastInsertId();
      return $user_id;
  }
  catch (PDOException $e) {
    return -1;
  }
}

function save_form_submission($user_id, $submission)
{
  global $db;
  try {
    $db->beginTransaction();
    $stmt = $db->prepare("INSERT INTO application 
        (user_id, name, phone, email, bdate, gender, bio) 
        VALUES (:user_id, :name, :phone, :email, :bdate, :gender, :bio);");
    $stmt->bindParam('user_id', $user_id);
    $stmt->bindParam('name', $submission['name']);
    $stmt->bindParam('phone', $submission['phone']);
    $stmt->bindParam('email', $submission['email']);
    $stmt->bindParam('bdate', $submission['date']);
    $gender = $submission['gender'] == "male" ? '1' : '0';
    $stmt->bindParam('gender', $gender);
    $stmt->bindParam('bio', $submission['bio']);
    $stmt->execute();

    $submission_rowid = $db->lastInsertId();
    foreach ($submission["fpls"] as $fpl) {
      $stmt = $db->prepare(sprintf("INSERT INTO fpls (parent_id, fpl) VALUES (%s, :fpl);", $submission_rowid));
      $stmt->bindParam('fpl', $fpl);
      $stmt->execute();
    }

    $db->commit();
    return true;
  } catch (Exception $e) {
    $db->rollback();
    return false;
  }
}

function update_sumbission_data($user_id, $submission) {
  global $db;
  try {
      $db->beginTransaction();
      $stmt = $db->prepare("UPDATE application 
      SET name = :name, phone = :phone, email = :email, bdate = :bdate, gender = :gender, bio = :bio
      WHERE user_id = :user_id");
      $stmt->bindParam('user_id', $user_id);
      $stmt->bindParam('name', $submission['name']);
      $stmt->bindParam('phone', $submission['phone']);
      $stmt->bindParam('email', $submission['email']);
      $stmt->bindParam('bdate', $submission['date']);
      $gender = $submission["gender"] == "male" ? '1' : '0';
      $stmt->bindParam('gender', $gender);
      $stmt->bindParam('bio', $submission['bio']);
      $stmt->execute();

      $stmt = $db->prepare("SELECT id from application WHERE user_id = :user_id");
      $stmt->bindParam("user_id", $user_id);
      $stmt->execute();
      $row_id = $stmt->fetchAll()[0]['id'];

      $stmt = $db->prepare("DELETE FROM fpls WHERE parent_id = :parent_id");
      $stmt->bindParam('parent_id', $row_id);
      $stmt->execute();

      foreach ($submission['fpls'] as $fpl) {
          $stmt = $db->prepare(sprintf("INSERT INTO fpls (parent_id, fpl) VALUES (%s, :fpl);", $row_id));
          $stmt->bindParam('fpl', $fpl);
          $stmt->execute();
      }

      $db->commit();
      return true;
  }
  catch (PDOException $e) {
      $db->rollback();
      return false;
  }
}

function get_user_form_submission($user_id)
{
  global $db;
  try {
    $stmt = $db->prepare('SELECT * FROM application WHERE
        user_id = :user_id');
    $stmt->bindParam('user_id', $user_id);
    $stmt->execute();

    return $stmt->fetchAll();
  } catch (Exception $e) {
    return false;
  }
}

function get_user_fpls($submission_id)
{
  global $db;
  try {
    $stmt = $db->prepare('SELECT fpl FROM fpls WHERE
        parent_id = :parent_id');
    $stmt->bindParam('parent_id', $submission_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  } catch (Exception $e) {
    return false;
  }
}
