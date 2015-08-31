<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/user.php';

$data->execStrong('CREATE TABLE IF NOT EXISTS Courses
(
    CourseID INTEGER PRIMARY KEY AUTOINCREMENT,
    CourseName TEXT NOT NULL,
    TeacherID TEXT NOT NULL,
    CourseObjectives TEXT NOT NULL,
    CourseContent TEXT NOT NULL,
    CourseEvaluation TEXT NOT NULL,
    CourseStartTime INTEGER NOT NULL,
    CourseEndTime INTEGER NOT NULL,
    CourseStudentCount INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS Entries
(
    StudentID INTEGER NOT NULL,
    CourseID INTEGER NOT NULL,
    PRIMARY KEY (StudentID, CourseID)
);');

function course_list() {
    global $data, $user;
    return $data->fetchArrayAll($data->prepare('SELECT CourseID, CourseName, TeacherName, CourseStartTime, ' .
        'CourseEndTime, CASE WHEN CourseEntryCount IS NULL THEN 0 ELSE CourseEntryCount END AS CourseEntryCount, ' .
        'CourseStudentCount' .
        ($user['Mode'] == 'student' ? ", CASE WHEN Entered IS NULL THEN 0 ELSE Entered END AS Entered" : '') .
        ' FROM Courses NATURAL LEFT JOIN Teachers NATURAL LEFT JOIN (SELECT CourseID, COUNT(StudentID) AS ' .
        'CourseEntryCount' . ($user['Mode'] == 'student'
            ? ", SUM(CASE WHEN StudentID=${user['StudentID']} THEN 1 ELSE 0 END) AS Entered" : '') .
        ' FROM Entries GROUP BY CourseID);')->execute());
}

function course_info($query) {
    global $data, $user;
    $id = isset($query['CourseID']) ? $query['CourseID'] : '';
    if ($query['CourseName']) {
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
        CourseEvaluation, CourseStartTime, CourseEndTime, ' .
        'CASE WHEN CourseEntryCount IS NULL THEN 0 ELSE CourseEntryCount END AS CourseEntryCount, CourseStudentCount' .
        ($user['Mode'] == 'student' ? ', CASE WHEN Entered IS NULL THEN 0 ELSE Entered END As Entered' : '') . ' FROM' .
        ' Courses NATURAL LEFT JOIN Teachers NATURAL LEFT JOIN (SELECT CourseID, COUNT(StudentID) AS CourseEntryCount' .
        ($user['Mode'] == 'student' ? ", SUM(CASE WHEN StudentID=${user['StudentID']} THEN 1 ELSE 0 END) AS Entered"
            : '') . ' FROM Entries GROUP BY CourseID) WHERE CourseID = :id');
    $statement->bindValue(':id', $id);
    return $data->fetchArray($statement->execute());
}

function course_delete($id) {
    global $data, $user;
    $statement = $data->prepare('DELETE FROM Courses WHERE CourseID = :id AND TeacherID = :uid;');
    $statement->bindValue(':id', $id);
    $statement->bindValue(':uid', $user['TeacherID']);
    if (is_string($msg = $data->executeWithError($statement))) return $msg;
    return $data->changes() ? null : '未找到符合条件的课程！';
}

function course_enter($id) {
    function preproc($course) {
        // this is pretty ridiculous but what if?
        if ($course['CourseEndTime'] < $course['CourseStartTime']) $course['CourseEndTime'] += 10080;
    }

    global $data, $user;
    $statement = $data->prepare('SELECT CourseID, CourseStartTime, CourseEndTime FROM Courses WHERE CourseID = :cid;');
    $statement->bindValue(':cid', $id);
    if (is_string($current = $data->fetchArray($statement->execute()))) return $current;
    preproc($current);
    // TODO: there is an obvious race condition but let's hope nothing bad ever happens mmmkay?
    $statement = $data->prepare('SELECT CourseID, CourseStartTime, CourseEndTime FROM Students ' .
        'NATURAL JOIN Entries NATURAL JOIN Courses WHERE StudentID = :sid ORDER BY CourseStartTime;');
    $statement->bindValue(':cid', $id);
    $statement->bindValue(':sid', $user['StudentID']);
    if (is_string($courses = $data->executeWithError($statement))) return $courses;
    $signOut = false;
    while ($course = $courses->fetchArray(SQLITE3_ASSOC)) {
        if ($course['CourseID'] == $current['CourseID']) {
            $signOut = true;
            break;
        }
        preproc($course);
        if ($current['CourseEndTime'] > $course['CourseStartTime'] &&
            $current['CourseStartTime'] < $course['CourseEndTime']) return '对不起，您选的课时间有重合。';
    }
    $statement = $data->prepare($signOut ? 'DELETE FROM Entries WHERE CourseID = :cid AND StudentID = :sid'
        : 'INSERT INTO Entries (CourseID, StudentID) VALUES (:cid, :sid)');
    $statement->bindValue(':cid', $id);
    $statement->bindValue(':sid', $user['StudentID']);
    return $data->executeWithError($statement);
}

function course_export($id) {
    global $data, $user;
    $statement = $data->prepare('SELECT TeacherID FROM Courses WHERE CourseID = :id;');
    $statement->bindValue(':id', $id);
    if (is_string($r = $data->fetchArray($statement->execute()))) fatal_alert($r);
    if ($user['Mode'] !== 'teacher' || $r['TeacherID'] !== $user['TeacherID']) fatal_alert('您无权执行此操作！');
    $statement = $data->prepare('SELECT StudentID, StudentName, StudentGender, StudentIntroduction FROM Entries '.
        'NATURAL LEFT JOIN Students WHERE CourseID = :id;');
    $statement->bindValue(':id', $id);
    if (is_string($r = $data->fetchArrayAll($statement->execute()))) fatal_alert($r);
    return $r;
}
