<?php
require_once dirname(__FILE__) . '/db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Entries
(
    StudentID INTEGER NOT NULL,
    CourseID INTEGER NOT NULL,
    PRIMARY KEY (StudentID, CourseID)
);');
