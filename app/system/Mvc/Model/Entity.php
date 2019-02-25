<?php
namespace App\System\Mvc\Model;

use Symfony\Component\HttpFoundation\ParameterBag;

class Entity {

    public function setData(array $data = []) {
        foreach ($data as $var => $val) {
            $this->{$var} = $val;
        }
        return $this;
    }

    public function getData() {
        $vars = get_class_vars(get_class($this));
        $result = new ParameterBag();
        foreach ($vars as $var => $defaultValue) {
            $result->set($var, isset($this->{$var}) ? $this->{$var} : $defaultValue);
        }
        return $result->all();
    }

}