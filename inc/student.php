<?php
require_once 'db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Students
(
    StudentID INTEGER PRIMARY KEY NOT NULL,
    Password TEXT NOT NULL,
    Name TEXT NOT NULL,
    Gender TEXT,
    Introduction TEXT
);');

function studentLogin() {
    global $data, $uid, $password, $user;
    $statement = $data->prepare('SELECT * FROM Students WHERE StudentID = :uid AND Password = :password;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    if ($user = $statement->execute()->fetchArray(SQLITE3_ASSOC)) $user['Mode'] = 'student';
}

function studentChangePassword($password) {
    global $data, $uid;
    $statement = $data->prepare('UPDATE Students SET Password = :password WHERE StudentID = :uid;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    return $data->executeWithError($statement);
}
