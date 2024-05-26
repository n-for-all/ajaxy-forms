<?php

namespace Ajaxy\Forms\Inc;

class Actions
{

    private $actions = array();
    static $instance = null;

    /**
     * Get instance of actions
     *
     * @date 2024-05-24
     *
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Actions();
        }
        return self::$instance;
    }

    /**
     * Get an action by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param string $default
     *
     * @return array|null
     */
    public function get($name, $default = null)
    {
        return isset($this->actions[$name]) ? $this->actions[$name] : ($default ? $this->actions[$default] : null);
    }

    /**
     * Set an action by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array $properties
     *
     * @return void
     */
    public function set($name, $properties)
    {
        $this->actions[$name] = $properties;
    }

    /**
     * Check if action is registered
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * Remove an action by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return void
     */
    public function remove($name)
    {
        unset($this->actions[$name]);
    }

    /**
     * Remove all actions
     *
     * @date 2024-05-24
     *
     * @return void
     */
    public function clear()
    {
        $this->actions = array();
    }

    /**
     * Get all actions
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_actions()
    {
        return $this->actions;
    }

    /**
     * Get properties of an action by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return array
     */
    public function get_properties($name)
    {
        $action = $this->get($name);
        $values = $action["properties"] ?? [];

        return $values;
    }

    /**
     * Register a new action
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array $properties
     *
     * @return void
     * 
     * @throws Exception if properties class is not valid and does not implement ActionInterface
     */
    public function register($name, $properties)
    {
        if (!isset($properties['class']) || !is_subclass_of($properties['class'], Actions\ActionInterface::class)) {
            throw new \Exception("Actions properties class must implement \Ajaxy\Forms\Inc\Actions\ActionInterface");
        }
        $this->actions[$name] = $properties;
    }
}
