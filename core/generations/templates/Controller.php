namespace Controllers;
use core\classes\BaseController;
use Models\{{model_name}};

class {{controller_name}} extends BaseController {
    public $private = true;
    private $model;
    protected $model_name = '{{model_name}}';
    
    public function __construct() {
        parent::__construct();
        $this->model = new {{model_name}}();
    }

}