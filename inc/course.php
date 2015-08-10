<?php
require_once dirname(__FILE__) . '/db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Courses
(
    CourseID INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL,
    TeacherID TEXT NOT NULL,
    Objectives TEXT,
    Content TEXT,
    Evaluation TEXT,
    StartTime INTEGER NOT NULL,
    EndTime INTEGER NOT NULL,
    StudentCount INTEGER NOT NULL
);');
