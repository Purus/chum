<?php

namespace Chum\Core\Models;

class Route
{

    /**
     * Route name.
     *
     * @var string
     */
    private $routeName;

    /**
     * Route URI pattern with vars (simple string for static routes). 
     *
     * @var string
     */
    private $routePath;

    /**
     * Decomposed URI parts.
     *
     * @var array
     */
    private $routePathArray;

    private $controller;
    private $action;
    private $method;

    /**
     * Flag indicating if route path is static.
     * 
     * @var boolean
     */
    private $isStatic = false;

    /**
     * Default route params.
     * 
     * @var array
     */
    private $routeParamOptions = array();

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @return string
     */
    public function getRoutePath()
    {
        return $this->routePath;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        if ($routeName) {
            $this->routeName = trim($routeName);
        }
    }

    /**
     * @param string $routePath
     */
    public function setRoutePath($routePath)
    {
        if ($routePath) {
            // $this->routePath = UTIL::removeFirstAndLastSlashes(trim($routePath));
            $this->routePath = trim($routePath);
            $this->routePathArray = explode('/', $this->routePath);
        }
    }

    /**
     * @param array $routeParamOptions
     */
    public function setRouteParamOptions(array $routeParamOptions)
    {
        $this->routeParamOptions = $routeParamOptions;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * Constructor.
     *
     * @throws \InvalidArgumentException
     * @param string $method
     * @param string $routeName
     * @param string $routePath
     * @param string $controller
     * @param string $action
     * @param array $paramOptions
     */
    public function __construct($method, $routeName, $routePath, $controller, $action, array $paramOptions = array())
    {
        if (empty($routeName) || empty($routePath) || empty($controller) || empty($action)) {
            throw new \InvalidArgumentException('Invalid route params provided!');
        }

        $this->method = $method;
        $this->controller = $controller;
        $this->action = $action;
        $this->routeParamOptions = $paramOptions;
        $this->routeName = trim($routeName);
        $this->setRoutePath($routePath);

        // if there are no dynamic parts in route path -> set flag and return
        if (!mb_strstr($this->routePath, ':')) {
            $this->isStatic = true;
            return;
        }
    }

    /**
     * Adds options to route params.
     *
     * @param string $paramName
     * @param string $option
     * @param mixed $optionValue
     */
    public function addParamOption($paramName, $option, $optionValue)
    {
        if (empty($this->routeParamOptions[$paramName])) {
            $this->routeParamOptions[$paramName] = array();
        }

        $this->routeParamOptions[$paramName][$option] = $optionValue;
    }


	/**
	 * @return mixed
	 */
	public function getController() {
		return $this->controller;
	}
	
	/**
	 * @param mixed $controller 
	 * @return self
	 */
	public function setController($controller): self {
		$this->controller = $controller;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * @param mixed $action 
	 * @return self
	 */
	public function setAction($action): self {
		$this->action = $action;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * @param mixed $method 
	 * @return self
	 */
	public function setMethod($method): self {
		$this->method = $method;
		return $this;
	}
}