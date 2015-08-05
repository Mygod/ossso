<?php
require_once 'db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Entries
(
    StudentID INTEGER NOT NULL,
    TeacherID TEXT NOT NULL,
    PRIMARY KEY (StudentID, TeacherID)
);');
