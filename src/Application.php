<?php

namespace spartaksun\addresses;


use spartaksun\addresses\components\UserAuth;

/**
 * Basic application class. Resolves routes and run actions
 * @package spartaksun\addresses
 */
class Application
{

    /**
     * @var array of router callbacks
     */
    private $_routes = array();
    /**
     * @var array application config
     */
    private $_config = array();


    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * @param $url
     * @param callable $handler
     */
    public function route($url, \Closure $handler)
    {
        $this->_routes[$url] = $handler;
    }

    /**
     * Start application
     * @throws AddressBookException
     */
    public function run()
    {
        session_start();

        $params = $_GET;

        $route = !empty($params['route']) ? $params['route'] : '/';
        $route = "/" . trim($route, "/");

        foreach ($this->_routes as $routeKey => $callback) {
            if ($routeKey === $route) {

                $nonExistParameters = array();
                $realParams = array();

                $refFunc = new \ReflectionFunction($callback);
                foreach ($refFunc->getParameters() as $refParameter) {
                    $name = $refParameter->getName();
                    if(array_key_exists($name, $params)){
                        $realParams[$name] = $params[$name];
                        continue;
                    }
                    $nonExistParameters[] = $name;
                }

                if(!empty($nonExistParameters)) {
                    throw new AddressBookException('Missing parameters: ' . implode(",", $nonExistParameters));
                }
                call_user_func_array($callback, $realParams);

                return;
            }
        }
        throw new AddressBookException("Route {$route} not found.");
    }

    /**
     * Render templates with parameters
     *
     * @param $view
     * @param array $params
     * @return void
     * @throws AddressBookException
     */
    public function render($view, $params = array())
    {
        ob_end_clean();

        $params['this'] = $this;

        $viewFileName = $this->_config['basePath'] . '/view/'  . $view . ".php";
        $layoutFileName = $this->_config['basePath'] . "/view/layout.php";

        if (file_exists($viewFileName) && file_exists($layoutFileName)) {

            extract($params);
            $data = file_get_contents($viewFileName);
            $data = str_replace("{{content}}", $data, file_get_contents($layoutFileName));

            ob_start();

            eval("?> " . $data . "<?php ");
            $output = ob_get_contents();

            ob_end_clean();

        } else {
            throw new AddressBookException("View ($viewFileName) or layout ({$layoutFileName}) file does not exist. ");
        }

        header('Content-Type: text/html; charset=utf-8');
        die($output);
    }

    /**
     * Http redirect
     * @param $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        die;
    }

    /**
     * Check if user authenticated? if not - does redirect
     */
    public function checkAuthenticate()
    {
        $auth = new UserAuth();
        if(!$auth->isAuthenticate()) {
            $this->redirect('/login');
        }
    }
}
