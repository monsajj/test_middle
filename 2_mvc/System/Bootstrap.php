<?php
class Bootstrap
{
    public function __construct() {
        $flag = FALSE;
        // 1. router
        if (isset ($_GET['path'])) {
            $tokens = explode('/', rtrim($_GET['path'], '/'));

            // 2. Dispatcher
            $controllerName = ucfirst(array_shift($tokens)) . 'Controller';
            if (file_exists('controllers/'.$controllerName.'.php')) {
                $controller = new $controllerName();
                if (!empty($tokens)) {
                    $actionName = array_shift($tokens);
                    if (method_exists ( $controller , $actionName )) {
                        $controller->{$actionName}(@$tokens);
                    }
                    else {
                        $flag = TRUE;
                    }
                }
                else
                {
                    // default action
                    $controller->index();
                }
            }
            else {
                $flag = TRUE;
            }
        }
        else {
            $controllerName = 'PageController';
            $controller = new $controllerName();
            $controller->index();
//            $controller->createPage([true, 'title', 'description']);
//            $controller->get(23);
//            $controller->updatePage(23, [true, 'update title', 'update description']);
//            $controller->deletePage(23);
        }

        //Error404 page
        if ( $flag ) {
            $controllerName = 'Error404Controller';
            $controller = new $controllerName();
            $controller->index();
        }
    }
}
