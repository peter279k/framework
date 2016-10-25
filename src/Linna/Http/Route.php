<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Http;

use Linna\Http\RouteInterface;

/**
 * Describe valid routes.
 *
 */
class Route implements RouteInterface
{
    /**
     * @var string $name Route name
     */
    protected $name;

    /**
     * @var string $method Indicates request method
     */
    protected $method;
    
    /**
     * @var string $view View to call
     */
    protected $view;
    
    /**
     * @var string $view View to call
     */
    protected $model;
    
    /**
     * @var string $controller Controller to call
     */
    protected $controller;
    
    /**
     * @var string $action Action to call
     */
    protected $action;

    /**
     * @var array $param Parameter passed to controller
     */
    protected $param;

    /**
     * Contructor
     *
     * @param string $name
     * @param string $method
     * @param string $model
     * @param string $view
     * @param string $controller
     * @param mixed $action
     * @param array  $param
     */
    public function __construct(string $name, string $method, string $model, string $view, string $controller, string $action, array $param)
    {
        $this->name = $name;
        $this->method = $method;
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
        $this->action = $action;
        $this->param = $param;
    }

    /**
     * Return model name
     *
     * @return string Model for call new $Model()
     */
    public function getModel(): string
    {
        return $this->model;
    }
    
    /**
     * Return view name
     *
     * @return string View for call new $View()
     */
    public function getView(): string
    {
        return $this->view;
    }
    
    /**
     * Return controller
     *
     * @return string Controller for call $controller->default_action()
     */
    public function getController(): string
    {
        return $this->controller;
    }
    
    /**
     * Return action name
     *
     * @return string Action for call $controller->action()
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Return parameters
     *
     * @return array Parameter for call $controller->action(Param)
     */
    public function getParam(): array
    {
        return $this->param;
    }
}
