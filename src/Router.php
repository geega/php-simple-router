<?php
namespace  Geega\SimpleRouter;

use Geega\Request\Request;

class Router {
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $map = [];

    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    private $nsControllerTpl = '';

    /**
     * Router constructor.
     * @param Request $request
     */
    public function __construct(Request $request, $nsControllerTpl)
    {
        $this->request = $request;
        $this->nsControllerTpl = $nsControllerTpl;
    }


    /**
     * @param $path
     * @param $params
     */
    public function get($path, $params)
    {
        $this->map['GET'][$path] = $params;
    }

    /**
     * @param $path
     * @param $params
     */
    public function post($path, $params)
    {
        $this->map['POST'][$path] = $params;
    }

    /**
     * Get current
     * @return $this
     * @throws \Exception
     */
    public function getCurrent()
    {
        if (empty($this->map[$this->request->requestMethod])) {
            throw new \Exception('Not found method');
        }
        $current_map = $this->map[$this->request->requestMethod];

        if (empty($current_map[$this->request->uri])) {
            $current_route = $current_map['/404'];
        } else {
            $current_route = $current_map[$this->request->uri];
        }
        $this->setRoute($current_route);
        return $this;
    }

    /**
     * @param $route
     * @throws \Exception
     */
    public function setRoute($route)
    {
        $route = explode('@', $route);
        if (empty($route[0])) {
            throw new \Exception('Not found controller');
        }

        $this->controller = str_replace('{{NAME}}', $route[0], $this->nsControllerTpl);
        if (empty($route[1])) {
            throw new \Exception('Not found method controller');
        }
        $this->method = $route[1];
    }

    /**
     * Get request
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}