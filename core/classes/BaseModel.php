<?php

namespace core\classes;
use PDO;
use PDOException;

abstract class BaseModel {
    // TODO: Definir métodos y propiedades necesarias para los controladores que extiendan esta clase.
    public $table = '';
    public $primaryKey = 'id';
    public $timestamps = true;
    public $private = false;
    public $canCreate = false;
    public $searchable = [];
    public $head = [];
    public $label = '';

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

    public function find($where = [], $type = '', $order = '', $limit = 10, $page = 1) {

        $columns = $this->columns();
        $selectFields = [];
        foreach ($columns as $colName => $colProps) {
            if (!empty($colProps['show_in_list'])) {
            if (isset($colProps['foreign_key'])) {
                $fk = $colProps['foreign_key'];
                $selectFields[] = "r.{$fk['display_column']} AS {$colName}";
            } else {
                $selectFields[] = "u.{$colName}";
            }
            }
        }
        $select = implode(', ', $selectFields);
        
        $whereClause = '';
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $field => $value) {
            $conditions[] = "u.{$field} LIKE '{$value}'";
            }
            $whereClause = 'WHERE ' . implode(' ' . $type . ' ', $conditions);
        }
        
        $orderClause = '';
        if (!empty($order)) {
            $orderClause = "ORDER BY {$order}";
        }
        
        $offset = ($page - 1) * $limit;
        
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} u {$whereClause}";
        $totalQuery = $this->query($countSql);
        $totalCount = $totalQuery[0]['total'] ?? 0;
        
        $sql = "SELECT $select FROM {$this->table} u LEFT JOIN roles r ON u.role_id = r.id {$whereClause} {$orderClause} LIMIT {$limit} OFFSET {$offset}";
        $rows = $this->query($sql);
        
        return [
            'rows' => $rows,
            'count' => $totalCount
        ];
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

    public function query($sql, $params = [], $count = false) {
        $db = new Db();
        $conn = $db->getConnection();

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}