<?php
require_once dirname(__FILE__) . '/db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Teachers
(
    TeacherID TEXT PRIMARY KEY NOT NULL,
    Password TEXT NOT NULL,
    Name TEXT NOT NULL
);');

function teacher_add($id, $password, $name) {
    global $data;
    $statement = $data->prepare(
        'INSERT OR REPLACE INTO Teachers (TeacherID, Password, Name) VALUES (:id, :password, :name);');
    $statement->bindValue(':id', $id);
    $statement->bindValue(':password', hash('sha512', $password));
    $statement->bindValue(':name', $name);
    return $data->executeWithError($statement);
}

function teacher_remove($id) {
    global $data;
    $statement = $data->prepare('DELETE FROM Teachers WHERE TeacherID = :id;');
    $statement->bindValue(':id', $id);
    return $data->executeWithError($statement);
}

function teacher_login() {
    global $data, $uid, $password, $user;
    $statement = $data->prepare('SELECT * FROM Teachers WHERE TeacherID = :uid AND Password = :password;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    if ($user = $statement->execute()->fetchArray(SQLITE3_ASSOC)) $user['Mode'] = 'teacher';
}

function teacher_change_password($password) {
    global $data, $uid;
    $statement = $data->prepare('UPDATE Teachers SET Password = :password WHERE TeacherID = :uid;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    return $data->executeWithError($statement);
}

function teacher_list() {
    global $data;
    return $data->fetchArrayAll($data->prepare('SELECT TeacherID, Name FROM Teachers;')->execute());
}
