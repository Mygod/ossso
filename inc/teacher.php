<?php
require_once 'db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Teachers
(
    TeacherID TEXT PRIMARY KEY NOT NULL,
    Password TEXT NOT NULL,
    Name TEXT NOT NULL
);');

function loginTeacher() {
    global $data, $uid, $password, $user;
    $statement = $data->prepare('SELECT * FROM Teachers WHERE TeacherID = :uid AND Password = :password;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    if ($user = $statement->execute()->fetchArray(SQLITE3_ASSOC)) $user['Mode'] = 'teacher';
}
