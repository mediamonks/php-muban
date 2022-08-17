<?php

declare(strict_types=1);

namespace App\Library;

use Symfony\Component\Validator\Constraints as Assert;

class CLA2Icon extends AbstractComponent
{
    protected string $component = 'cl-a2-icon';

    private string $name;

    public static function fromObject(object $params): CLA2Icon
    {
        $component = new self();

        // Default properties
        $component->className = $params->className ?? null;
        $component->id = $params->id ?? null;

        // Custom properties
        $component->name = $params->name;

        return $component;
    }

    public function rules(): Assert\Collection
    {
        return $this->extendBaseRules([
            'name' => new Assert\NotBlank(),
        ]);
    }

    public function getName(): string
    {
        return $this->name;
    }
}