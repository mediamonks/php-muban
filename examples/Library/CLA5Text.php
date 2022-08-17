<?php

declare(strict_types=1);

namespace App\Library;

use Symfony\Component\Validator\Constraints as Assert;

class CLA5Text extends AbstractComponent
{
    protected string $component = 'cl-a5-text';

    private string $copy;
    private string $style = 'copy-1';
    private string $as = 'p';

    public static function fromObject(object $params): CLA5Text
    {
        $component = new self();

        // Default properties
        $component->className = $params->className ?? null;
        $component->id = $params->id ?? null;

        // Custom properties
        $component->copy = $params->copy;
        $component->style = $params->style ?? $component->style;
        $component->as = $params->as ?? $component->as;

        return $component;
    }

    public function rules(): Assert\Collection
    {
        return $this->extendBaseRules([
            'copy' => new Assert\NotBlank(),
            'style' => new Assert\NotBlank(),
            'as' => new Assert\NotBlank(),
        ]);
    }

    public function getCopy(): string
    {
        return $this->copy;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function getAs(): string
    {
        return $this->as;
    }
}