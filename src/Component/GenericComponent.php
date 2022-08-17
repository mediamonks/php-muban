<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Component;

class GenericComponent
{
    public function __construct(private readonly string $component, private readonly object $params)
    {
    }

    public function getComponent(): string {
        return $this->component;
    }

    public function getParameters(): object
    {
        return $this->params;
    }

    public function __get(string $name)
    {
        if (isset($this->$name)) return $this->$name;

        return $this->params->$name;
    }

    public function __call(string $name, array $args) {
        if (isset($this->$name)) return $this->$name;

        return $this->params->$name;
    }
}