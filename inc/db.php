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
}

class Data extends Db {
    function __construct() {
        $this->openStrong($_SERVER['DOCUMENT_ROOT'] . '/data/data.db');
    }
}
$data = new Data();
