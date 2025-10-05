<?php

namespace Controllers;
use Config\Db;
use Config\Controller;
use PDO;

class Migrations extends Controller {

    public $private = true;

    public function __construct() {
        $db = new Db();
        $conn = $db->getConnection();
    }

    public function index() {        
        $models = array_diff(scandir('models'), array('.', '..'));
        foreach ($models as $model) {
            $model_name = str_replace('.php', '', $model);
            if (class_exists($model_name)) {
                $instance = new $model_name();
                if (method_exists($instance, 'columns')) {
                    $columns = $instance->columns();
                    $table = $instance->table;
                    if (!empty($columns)) {
                        $db = new Db();
                        $conn = $db->getConnection();

                        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
                        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                        if (empty($tables)) {
                            // Construir columnas principales
                            $columns_sql = ["id INT(11) AUTO_INCREMENT PRIMARY KEY"];
                            // Agregar columnas del modelo
                            foreach ($columns as $column_name => $attributes) {
                                $type = strtoupper($attributes['type']);
                                $max_length = isset($attributes['max_length']) ? "({$attributes['max_length']})" : '';
                                $required = isset($attributes['required']) && $attributes['required'] ? 'NOT NULL' : 'NULL';
                                $default = isset($attributes['default']) ? "DEFAULT '{$attributes['default']}'" : '';
                                $columns_sql[] = "$column_name $type$max_length $required $default";
                            }
                            // Agregar timestamps al final
                            if ($instance->timestamps) {
                                $columns_sql[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
                                $columns_sql[] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
                            } else {
                                $columns_sql[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
                            }
                            $sql = "CREATE TABLE $table (" . implode(', ', $columns_sql) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
                            $conn->exec($sql);
                        }

                        foreach ($columns as $column_name => $attributes) {
                            $stmt = $conn->query("SHOW COLUMNS FROM $table LIKE '$column_name'");
                            $existing_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            if (empty($existing_columns)) {
                                $type = strtoupper($attributes['type']);
                                $max_length = isset($attributes['max_length']) ? "({$attributes['max_length']})" : '';
                                $required = isset($attributes['required']) && $attributes['required'] ? 'NOT NULL' : 'NULL';
                                $default = isset($attributes['default']) ? "DEFAULT '{$attributes['default']}'" : '';
                                // Añadir la columna antes de los timestamps si existen, sino al final
                                $after = $instance->timestamps ? " AFTER id" : "";
                                $sql = "ALTER TABLE $table ADD COLUMN $column_name $type$max_length $required $default$after";
                                $conn->exec($sql);
                            }
                        }
                    }
                } else {
                    echo "No columns method in: " . $model_name . "<br>";
                }
            } else {
                echo "Class does not exist: " . $model_name . "<br>";
            }
        }
    }

    public function seeders($seeders = []) {
        if(empty($seeders)) {
            $seeders = array_diff(scandir('seeders'), array('.', '..'));
        } else {
            $seeders = array_map(function($seeder) {
                return strpos($seeder, '.php') === false ? $seeder . '.php' : $seeder;
            }, $seeders);
        }

        $imports = '';
        foreach ($seeders as $seeder) {
            require_once 'seeders/' . ucfirst($seeder);
            $model_name = str_replace('.php', '', ucfirst($seeder));

            if (class_exists($model_name)) {
                $instance = new $model_name();
                if (method_exists($instance, 'create')) {
                    foreach($seed as $data) {
                        $instance->create($data);
                    }
                    $imports .= "Seeded: " . $model_name . "<br>";
                } else {
                    echo "No create method in: " . $model_name . "<br>";
                }
            } else {
                echo "Class does not exist: " . $model_name . "<br>";
            }
        }

        echo "Se han importado los siguientes datos:<br>" . $imports;
    }

    public function all($fresh) {
        if($fresh === 'fresh') {
            $db = new Db();
            $conn = $db->getConnection();
            $stmt = $conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $conn->exec("DROP TABLE IF EXISTS $table");
            }
        }

        $this->index();
        $this->seeders();
    }

}