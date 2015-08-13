<?php
class Db extends SQLite3 {
    function selfDestruction() {
        die($this->lastErrorMsg());
    }

    function execStrong($command) {
        $this->exec($command) or $this->selfDestruction();
    }

    function openStrong($path) {
        $this->open($path);
        if (!$this) $this->selfDestruction();
    }

    function executeWithError(SQLite3Stmt $statement) {
        return ($result = $statement->execute()) ? $result : $this->lastErrorMsg();
    }

    function fetchArray(SQLite3Result $query) {
        return $query ? $query->fetchArray(SQLITE3_ASSOC) : $this->lastErrorMsg();
    }

    function fetchArrayAll(SQLite3Result $query) {
        if (!$query) return $this->lastErrorMsg();
        $result = [];
        while ($row = $query->fetchArray(SQLITE3_ASSOC)) $result[] = $row;
        return $result;
    }
}

class Data extends Db {
    function __construct() {
        $this->openStrong($_SERVER['DOCUMENT_ROOT'] . '/data/data.db');
    }
}
$data = new Data();
