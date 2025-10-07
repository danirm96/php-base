namespace Models;
use core\classes\BaseModel;

class {{model_name}} extends BaseModel
{
    private $isLogged;

    public $table = '{{table_name}}';
    public $primaryKey = 'id';
    public $timestamps = true;
    
    public $searchable = [];

    public $head = [];

    public $label = '{{table_name}}';

    public $actions = [
        'view' => ['Ver', '/users/view'],
        'edit' => ['Editar', '/users/edit'],
        'delete' => ['Eliminar', '/users/delete'],
    ];


    public function columns(): array
    {
        return array(
            'id' => array(
                'type' => 'int',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => false,
                'auto_increment' => true,
                'label' => 'ID'
            )
        );
    }
}
