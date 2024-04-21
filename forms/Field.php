<?php

namespace MVC\Forms;


use MVC\Core\Model;


class Field extends BaseField
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_FILE = 'file';
    const TYPE_DATE = 'date';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->disabled = false;
        parent::__construct($model, $attribute);
    }

    public function renderInput()
    {
        return sprintf('<input type="%s" class="form-control%s" name="%s" value="%s" %s>',
            $this->type,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->model->{$this->attribute},
            $this->disabled ? 'disabled="disabled"' : ''
        );
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function readOnlyPasswordField()
    {
        $this->type = self::TYPE_PASSWORD;
        $this->disabled = true;
        return $this;
    }

    public function readOnlyField()
    {
        $this->type = self::TYPE_TEXT;
        $this->disabled = true;
        return $this;
    }

    public function fileField()
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }

    public function dateField()
    {
        $this->type = self::TYPE_DATE;
        return $this;
    }
}