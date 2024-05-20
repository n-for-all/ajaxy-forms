<?php

namespace Ajaxy\Forms\Inc;

use Symfony\Component\Form\Extension\Core\Type;

class Actions
{

    private $actions = array();
    static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Actions();
        }
        return self::$instance;
    }

    public function get($name, $default = null)
    {
        return isset($this->actions[$name]) ? $this->actions[$name] : ($default ? $this->actions[$default] : null);
    }

    public function set($name, $properties)
    {
        $this->actions[$name] = $properties;
    }

    public function has($name)
    {
        return isset($this->actions[$name]);
    }

    public function remove($name)
    {
        unset($this->actions[$name]);
    }

    public function clear()
    {
        $this->actions = array();
    }

    public function get_actions()
    {
        return $this->actions;
    }

    public function get_properties($name)
    {
        $action = $this->get($name);
        $values = $action["properties"] ?? [];

        return $values;
    }

    public function register($name, $properties)
    {
        if (!isset($properties['class']) || !is_subclass_of($properties['class'], Actions\ActionInterface::class)) {
            throw new \Exception("Actions properties class must implement \Ajaxy\Forms\Inc\Actions\ActionInterface");
        }
        $this->actions[$name] = $properties;
    }
}
