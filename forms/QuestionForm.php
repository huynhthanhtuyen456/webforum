<?php

namespace MVC\Forms;


class QuestionForm extends Form
{
    public static function begin($action, $method, $options = [])
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        echo sprintf('<form enctype="multipart/form-data" action="%s" method="%s" %s>', $action, $method, implode(" ", $attributes));
        return new Form();
    }
}

?>