<?php
require_once 'db.php';
class Config extends Db {
    function __construct() {
        $this->openStrong($_SERVER['DOCUMENT_ROOT'] . '/data/config.db');
        $this->execStrong('CREATE TABLE IF NOT EXISTS Config
            (
                Key TEXT PRIMARY KEY NOT NULL,
                Value TEXT
            );');
    }

    public function get($key, $default) {
        $statement = $this->prepare('SELECT Value FROM Config WHERE Key = :key;');
        $statement->bindValue(':key', $key);
        return ($row = $statement->execute()->fetchArray(SQLITE3_ASSOC)) && $row['Value'] ? $row['Value'] : $default;
    }

    public function set($key, $value) {
        $statement = $this->prepare('INSERT OR REPLACE INTO Config (Key, Value) values (:key, :value);');
        $statement->bindValue(':key', $key);
        $statement->bindValue(':value', $value);
        return $this->executeWithError($statement);
    }

    public function getSiteName() { return $this->get('site_name', '选课系统'); }
    public function setSiteName($value) { return $this->set('site_name', $value); }

    // hash('sha512', 'Please change your default password.', false)
    public function getAdminPassword() { return $this->get('admin_password', 'acf2add116dafe5d22a9f383f088d8c4f45f7911da02e6f07f429fe6d11ca09620c36a4097cadd766483d10db94cb47e63d61cba6ac6cef4685b33cfaa18119b'); }
    public function setAdminPassword($value) { return $this->set('admin_password', $value); }
}
$config = new Config();
