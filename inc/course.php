<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/user.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Courses
(
    CourseID INTEGER PRIMARY KEY AUTOINCREMENT,
    CourseName TEXT NOT NULL,
    TeacherID TEXT NOT NULL,
    CourseObjectives TEXT,
    CourseContent TEXT,
    CourseEvaluation TEXT,
    CourseStartTime INTEGER NOT NULL,
    CourseEndTime INTEGER NOT NULL,
    CourseStudentCount INTEGER NOT NULL
);');

function course_list() {
    global $data, $user;
    return $data->fetchArrayAll($data->prepare(
        'SELECT CourseID, CourseName, TeacherName, CourseStartTime, CourseEndTime,
                COUNT(CourseID) AS CourseEntryCount, CourseStudentCount' . ($user['Mode'] == 'student'
            ? ", SUM(CASE WHEN StudentID=${user['StudentID']} THEN 1 ELSE 0 END) AS Entered" : '') .
        ' FROM Courses NATURAL LEFT JOIN Teachers NATURAL LEFT JOIN Entries GROUP BY CourseID;')->execute());
}

function course_info($query, $user) {
    global $data, $user;
    $id = isset($query['CourseID']) ? $query['CourseID'] : '';
    if (isset($query['CourseName'])) {
        if ($user['Mode'] == 'teacher') {
            $statement = $data->prepare($id
                ? 'UPDATE Courses SET CourseName = :cn, CourseObjectives = :co, CourseContent = :cc, ' .
                    'CourseEvaluation = :ce, CourseStartTime = :cst, CourseEndTime = :cet, CourseStudentCount = :csc ' .
                    'WHERE CourseID = :cid AND TeacherID = :tid;'
                : 'INSERT INTO Courses (CourseName, TeacherID, CourseObjectives, CourseContent, CourseEvaluation, ' .
                    'CourseStartTime, CourseEndTime, CourseStudentCount) ' .
                    'VALUES (:cn, :tid, :co, :cc, :ce, :cst, :cet, :csc);');
            if ($id) $statement->bindValue(':cid', $id);
            $statement->bindValue(':cn', $query['CourseName']);
            $statement->bindValue(':tid', $query['TeacherID']);
            $statement->bindValue(':co', $query['CourseObjectives']);
            $statement->bindValue(':cc', $query['CourseContent']);
            $statement->bindValue(':ce', $query['CourseEvaluation']);
            $statement->bindValue(':cst', $query['CourseStartTime']);
            $statement->bindValue(':cet', $query['CourseEndTime']);
            $statement->bindValue(':csc', $query['CourseStudentCount']);
            if (is_string($result = $data->executeWithError($statement))) return $result;
            if (!$id) $query['CourseID'] = $data->lastInsertRowID();
        }
        return $query;
    }
    $statement = $data->prepare('SELECT CourseID, CourseName, TeacherID, TeacherName, CourseObjectives, CourseContent,
        CourseEvaluation, CourseStartTime, CourseEndTime, COUNT(CourseID) AS CourseEntryCount, CourseStudentCount' .
        ($user['Mode'] == 'student'
            ? ", SUM(CASE WHEN StudentID=${user['StudentID']} THEN 1 ELSE 0 END) AS Entered" : '') .
        ' FROM Courses NATURAL LEFT JOIN Teachers NATURAL LEFT JOIN Entries WHERE CourseID = :id GROUP BY CourseID;');
    $statement->bindValue(':id', $id);
    return $data->fetchArray($statement->execute());
}
