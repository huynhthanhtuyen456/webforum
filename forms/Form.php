<?php


namespace MVC\Forms;


use MVC\Core\Model;


class Form
{
    public static function begin($action, $method, $options = [])
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        echo sprintf('<form action="%s" method="%s" %s>', $action, $method, implode(" ", $attributes));
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }

    public function textAreaField(Model $model, $attribute)
    {
        return new TextareaField($model, $attribute);
    }

    public function imageField(Model $model, $attribute)
    {
        return new ImageField($model, $attribute);
    }
}