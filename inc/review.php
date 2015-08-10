<?php
require_once dirname(__FILE__) . '/db.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Reviews
(
    ReviewID INTEGER PRIMARY KEY AUTOINCREMENT,
    Type INTEGER NOT NULL,
    StudentID INTEGER NOT NULL,
    TeacherID TEXT NOT NULL,
    Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    Score INTEGER NOT NULL,
    Comment TEXT
);');
