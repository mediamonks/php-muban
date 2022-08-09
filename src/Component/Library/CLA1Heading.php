<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Component\Library;

use Symfony\Component\Validator\Constraints as Assert;

class CLA1Heading extends AbstractComponent
{
    protected string $component = 'cl-a1-heading';

    private string $as;

    private ?string $copy;

    private ?string $ariaLabel;

    private ?string $style;

    public static function fromArray(array $params): CLA1Heading
    {
        $component = new self();

        // Default properties
        $component->className = $params['className'] ?? null;
        $component->id = $params['id'] ?? null;

        // Custom properties
        $component->as = $params['as'];
        $component->copy = $params['copy'];
        $component->style = $params['style'];
        $component->ariaLabel = $params['ariaLabel'] ?? null;

        return $component;
    }

    public function rules(): Assert\Collection
    {
        return $this->extendBaseRules([
            'as' => [
                new Assert\NotBlank(),
                new Assert\Choice(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])
            ],
            'copy' => new Assert\NotBlank(),
            'style' => new Assert\NotBlank(),
            'ariaLabel' => new Assert\Optional([
                new Assert\Type(['string']),
            ]),
        ]);
    }

    public function getAs(): string
    {
        return $this->as;
    }

    public function getCopy(): ?string
    {
        return $this->copy;
    }

    public function getAriaLabel(): ?string
    {
        return $this->ariaLabel;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }
}