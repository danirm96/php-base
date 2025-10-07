<?php

namespace core\classes;

abstract class BaseController {
    // TODO: Definir métodos y propiedades necesarias para los controladores que extiendan esta clase.
    public $private = false;
    private $model;
    protected $model_name;

    public function __construct() {
        global $model;
        if ($this->model_name) {
            $modelClass = "Models\\" . $this->model_name;
            $this->model = new $modelClass();
            $model = $this->model;
        }
    }

    public function list($params) {

        $page = $_GET['page'] ?? 1;
        $limit = $params[1] ?? 10;
        $search = $_GET['search'] ?? '';

        if ($search !== '' && property_exists($this->model, 'searchable')) {
            $where = [];
            foreach ($this->model->searchable as $field) {
                $where[$field] = "%$search%";
            }
        } else {
            $where = '';
        }

        $data = $this->model->find($where, 'OR', '', $limit, $page);
        $pages = ceil($data['count'] / $limit);

        charge_layout(
            'general',
            ['templates/list'],
            [
                'title' => $this->model->label . ' List',
                'head' => $this->model->head ?? [],
                'rows' => $data['rows'] ?? [],
                'primary_key' => $this->model->primaryKey,
                'current_page' => $page,
                'pages' => $pages,
                'limit' => $limit,
                'actions' => $this->model->actions ?? [],
                'templates' => true,
                'label' => $this->model->label,
                'plural' => $this->model->plural ?? $this->model->label ,
                'search' => $search
            ]
        );
    }

    public function view($params) {
        $id = $params[0] ?? null;
        if (!$id) {
            error_404(10);
            exit();
        }

        $data = $this->model->find([$this->model->primaryKey => $id]);
        if (empty($data['rows'])) {
            error_404(11);
            exit();
        }
        $item = $data['rows'][0];

        charge_layout(
            'general',
            ['templates/form'],
            [
                'title' => 'View ' . $this->model->label,
                'data' => $item,
                'label' => $this->model->label,
                'plural' => $this->model->plural ?? $this->model->label,
                'templates' => true,
                'type' => 'view',
                'model' => $this->model
            ]
        );
    }

    public function edit($params) {
        $id = $params[0] ?? null;
        if (!$id) {
            error_404(12);
            exit();
        }

        $data = $this->model->find([$this->model->primaryKey => $id]);
        if (empty($data['rows'])) {
            error_404(13);
            exit();
        }
        $item = $data['rows'][0];


        charge_layout(
            'general',
            ['templates/form'],
            [
                'title' => 'Edit ' . $this->model->label,
                'data' => $item,
                'label' => $this->model->label,
                'plural' => $this->model->plural ?? $this->model->label,
                'templates' => true,
                'type' => 'edit',
                'model' => $this->model
            ]
        );
    }

    public function update($params) {

        $id = isset($params[0]) ? $params[0] : ($_POST[$this->model->primaryKey] ?? null);
        if (!$id) {
            error_404(14);
            exit();
        }

        $data = $this->model->findBy($this->model->primaryKey, $id);
        if (empty($data)) {
            error_404(15);
            exit();
        }

        $item = $data[0];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [];
            foreach ($this->model->columns() as $field => $props) {
                if (isset($_POST[$field])) {
                    if (isset($props['encrypted']) && $props['encrypted'] === true) {
                        if ($_POST[$field] !== '') {
                            $updateData[$field] = password_hash($_POST[$field], PASSWORD_BCRYPT);
                        }
                    } else {
                        $updateData[$field] = $_POST[$field];
                    }
                }
            }
            $this->model->update($id, $updateData);
            header('Location: ' . BASE_URL . '/' . strtolower($this->model_name) . 's/list');
            exit();
        }

        charge_layout(
            'general',
            ['templates/form'],
            [
                'title' => 'Edit ' . $this->model->label,
                'data' => $item,
                'label' => $this->model->label,
                'plural' => $this->model->plural ?? $this->model->label,
                'templates' => true,
                'type' => 'edit',
                'model' => $this->model
            ]
        );
    }

    public function new($params) {

        charge_layout(
            'general',
            ['templates/form'],
            [
                'title' => 'Create ' . $this->model->label,
                'data' => [],
                'label' => $this->model->label,
                'plural' => $this->model->plural ?? $this->model->label,
                'templates' => true,
                'type' => 'create',
                'model' => $this->model
            ]
        );
    }

    public function create($params) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_POST[$this->model->primaryKey]);
            $createData = [];
            $errors = [];
            $uniqueFields = [];
            foreach ($this->model->columns() as $field => $props) {
                if (isset($props['unique']) && $props['unique'] === true) {
                    $uniqueFields[] = $field;
                }
                if (isset($_POST[$field])) {
                    if (isset($props['encrypted']) && $props['encrypted'] === true) {
                        if ($_POST[$field] !== '') {
                            $createData[$field] = password_hash($_POST[$field], PASSWORD_BCRYPT);
                        }
                    } else {
                        $createData[$field] = $_POST[$field];
                    }
                }
                
                // Verificar campos obligatorios
                if (isset($props['required']) && $props['required'] === true && (!isset($props['auto_increment']) || $props['auto_increment'] === false)) {
                    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                        $errors[$field] = "El campo {$field} es obligatorio";
                    }
                }
            }


            // Verificar unicidad
            foreach ($uniqueFields as $field) {
                if (isset($createData[$field])) {
                    $existing = $this->model->findBy($field, $createData[$field]);
                    if (!empty($existing)) {
                        $errors[$field] = "El valor para {$field} ya existe. Debe ser único.";
                    }
                }
            }

            if (!empty($errors)) {
                charge_layout(
                    'general',
                    ['templates/form'],
                    [
                        'title' => 'Create ' . $this->model->label,
                        'data' => $_POST,
                        'label' => $this->model->label,
                        'plural' => $this->model->plural ?? $this->model->label,
                        'templates' => true,
                        'type' => 'create',
                        'model' => $this->model,
                        'errors' => $errors
                    ]
                );
                exit();
            }
            $create = $this->model->create($createData);
            if($create) {
                charge_layout(
                    'general',
                    ['templates/form'],
                    [
                        'title' => 'Create ' . $this->model->label,
                        'data' => [],
                        'label' => $this->model->label,
                        'plural' => $this->model->plural ?? $this->model->label,
                        'templates' => true,
                        'type' => 'create',
                        'model' => $this->model,
                        'success' => 'Registro creado exitosamente'
                    ]
                );
            }
        }

        charge_layout(
            'general',
            ['templates/form'],
            [
                'title' => 'Create ' . $this->model->label,
                'data' => [],
                'label' => $this->model->label,
                'plural' => $this->model->plural ?? $this->model->label,
                'templates' => true,
                'type' => 'create',
                'model' => $this->model
            ]
        );
    }


}