<?php
require_once dirname(__FILE__) . '/db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Students
(
    StudentID INTEGER PRIMARY KEY NOT NULL,
    StudentPassword TEXT NOT NULL,
    StudentName TEXT NOT NULL,
    StudentGender TEXT,
    StudentIntroduction TEXT
);');

function student_add($id, $password, $name, $gender) {
    global $data;
    $statement = $data->prepare('INSERT OR REPLACE INTO Students (StudentID, StudentPassword, StudentName, ' .
        'StudentGender) VALUES (:id, :password, :name, :gender);');
    $statement->bindValue(':id', $id);
    $statement->bindValue(':password', hash('sha512', $password));
    $statement->bindValue(':name', $name);
    $statement->bindValue(':gender', $gender);
    return $data->executeWithError($statement);
}

function student_remove($id) {
    global $data;
    $statement = $data->prepare('DELETE FROM Students WHERE StudentID = :id;');
    $statement->bindValue(':id', $id);
    return $data->executeWithError($statement);
}

function student_login() {
    global $data, $uid, $password, $user;
    $statement = $data->prepare('SELECT * FROM Students WHERE StudentID = :uid AND StudentPassword = :password;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    if ($user = $statement->execute()->fetchArray(SQLITE3_ASSOC)) $user['Mode'] = 'student';
}

function student_change_password($password) {
    global $data, $uid;
    $statement = $data->prepare('UPDATE Students SET StudentPassword = :password WHERE StudentID = :uid;');
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':password', $password);
    return $data->executeWithError($statement);
}

function student_list() {
    global $data;
    return $data->fetchArrayAll($data->prepare('SELECT StudentID, StudentName, StudentGender FROM Students;')
        ->execute());
}
