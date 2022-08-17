<?php

declare(strict_types=1);

namespace App\Library;

use Symfony\Component\Validator\Constraints as Assert;

class CLA3Image extends AbstractComponent
{
    protected string $component = 'cl-a3-image';

    private string $src;

    private string $alt;

    private array $sources;

    private bool $enableLazyLoading;

    private bool $enableTransitionIn;

    private ?string $objectFit;

    public static function fromObject(object $params): CLA3Image
    {
        $component = new self();

        // Default properties
        $component->className = $params->className ?? null;
        $component->id = $params->id ?? null;

        // Custom properties
        $component->src = $params->src;
        $component->alt = $params->alt;
        $component->sources = $params->sources;
        $component->enableLazyLoading = $params->enableLazyLoading;
        $component->enableTransitionIn = $params->enableTransitionIn;
        $component->objectFit = $params->objectFit;

        return $component;
    }

    public function rules(): Assert\Collection
    {
        return $this->extendBaseRules([
            'src' => new Assert\NotBlank(),
            'alt' => new Assert\NotBlank(),
            'sources' => new Assert\Type(['type' => ['array', 'null']]),
            'enableLazyLoading' => new Assert\Type(['type' => 'boolean']),
            'enableTransitionIn' => new Assert\Type(['type' => 'boolean']),
            'objectFit' => new Assert\Choice(['cover', 'contain', 'scale-down', 'none', 'undefined']),
        ]);
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function getSources(): array
    {
        return $this->sources;
    }

    public function isEnableLazyLoading(): bool
    {
        return $this->enableLazyLoading;
    }

    public function isEnableTransitionIn(): bool
    {
        return $this->enableTransitionIn;
    }

    public function getObjectFit(): ?string
    {
        return $this->objectFit;
    }

    public function getClassName(): array
    {
        $className = is_string($this->className) ? [$this->className] : $this->className;

        $className[] = 'fit-' . $this->objectFit;
        if ($this->enableTransitionIn && $this->enableLazyLoading) $className[] = 'enable-transition-in';

        return $className;
    }
}