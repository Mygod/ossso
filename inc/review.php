<?php
require_once dirname(__FILE__) . '/db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Reviews
(
    ReviewID INTEGER PRIMARY KEY AUTOINCREMENT,
    ReviewType INTEGER NOT NULL,
    StudentID INTEGER NOT NULL,
    TeacherID TEXT NOT NULL,
    ReviewTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    ReviewScore INTEGER NOT NULL,
    ReviewComment TEXT
);');
