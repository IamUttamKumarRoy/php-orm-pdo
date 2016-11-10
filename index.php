<?php
// Default allow requests from everywhere. You can remove it if you want
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

define('DS', DIRECTORY_SEPARATOR);

use SimpleORM\core\helper\Helper;
use SimpleORM\core\model\Model;
use SimpleORM\core\controller\Controller;
use SimpleORM\core\model\AppModel;

$uri = $_SERVER['REQUEST_URI'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': $the_request = &$_GET;
        break;
    case 'POST': $the_request = &$_POST;
        break;
    default:
        $the_request = null;
}

if ($uri == '/') {
    $response = ['response' => 'No content to show'];
    echo json_encode($response);
} else {
    $src = explode('/', $uri);
    $model = ucfirst($src[1]);
    $controller = $model.'Controller';
    $method = (isset($src[2])) ? $src[2] : 'index';

    if (isset($src[3]) && empty($the_request)) {
        $the_request = filter_var($src[3], FILTER_SANITIZE_STRING);
    }

    /*
    * require files of current Model/Controller
    */
    $model_file = __DIR__ . DS .'app' . DS. ' model ' . DS . $model.'.php';

    if (file_exists($model_file)) {
        require_once $model_file;
    }

    $controller_file = __DIR__ . DS . 'app' . DS . 'controller' . DS . $controller.'.php';

    if (file_exists($controller_file)) {
        require_once $controller_file;
    } else {
        throw new Exception('Controller '.$controller.' Not Found');
    }

    /*
    * call current class/method
    */
    $class = new $controller();
    $set = $class->$method($the_request);

    /*
    * Declare all variables if passed in return
    */
    if (!empty($set) && is_array($set)) {
        foreach ($set as $k => $v) {
            ${$k} = $v;
        }
    }

    /*
    * If method has a view file, include
    */
    $view_file = __DIR__ . DS . 'app' . DS . 'view' . DS . $model . DS . $method .'.php';

    if (file_exists($view_file)) {
        include $view_file;
    }
}
