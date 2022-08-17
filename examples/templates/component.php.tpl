<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Component\Library;

use Symfony\Component\Validator\Constraints as Assert;

class [classname] extends AbstractComponent
{
    protected string $component = '[componentname]';

    [properties]

    public static function fromArray(array $params): [classname]
    {
        $component = new self();

        // Default properties
        $component->className = $params['className'] ?? null;
        $component->id = $params['id'] ?? null;

        // Custom properties
        [property_initializers]

        return $component;
    }

    public function rules(): Assert\Collection
    {
        return $this->extendBaseRules([

        ]);
    }

    [property_accessors]
}