<?php

namespace Config;
use Config\Db;
use PDO;
use PDOException;

class Model {
    public $table = '';
    public $primaryKey = 'id';
    public $timestamps = true;

    public function __construct() {
    }

    public function columns() {
        return array();
    }

    public function create($obj) {

        $db = new Db();
        $conn = $db->getConnection();

        $cols = $this->columns();
        $fields = array();
        $placeholders = array();
        $values = array();

        foreach ($cols as $col => $meta) {
            if (isset($obj[$col])) {
                $fields[] = $col;
                $placeholders[] = ':' . $col;
                $values[':' . $col] = $obj[$col];
            }
        }

        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }

    public function all() {
        $db = new Db();
        $conn = $db->getConnection();

        $columns = $this->columns();

        if (!empty($columns)) {
            $visible_cols = array();
            foreach ($columns as $col => $meta) {
                if (isset($meta['show_in_list']) && $meta['show_in_list']) {
                    $visible_cols[] = $col;
                }
            }
            if (!empty($visible_cols)) {
                $cols_str = implode(',', $visible_cols);
            } else {
                $cols_str = '*';
            }
        } else {
            $cols_str = '*';
        }

        $sql = "SELECT $cols_str FROM {$this->table}";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function find($id) {
        $db = new Db();
        $conn = $db->getConnection();

        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function findBy($field, $value) {
        $db = new Db();
        $conn = $db->getConnection();

        $sql = "SELECT * FROM {$this->table} WHERE $field = :value LIMIT 1";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $obj) {
        $db = new Db();
        $conn = $db->getConnection();

        $cols = $this->columns();
        $fields = array();
        $values = array();

        foreach ($cols as $col => $meta) {
            if (isset($obj[$col])) {
                $fields[] = "$col = :$col";
                $values[':' . $col] = $obj[$col];
            }
        }

        if (empty($fields)) {
            return false; // Nothing to update
        }

        $values[':id'] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id = :id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        $db = new Db();
        $conn = $db->getConnection();

        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}