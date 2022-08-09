<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Component\Library;

use Symfony\Component\Validator\Constraints as Assert;

class CLM1Button extends AbstractComponent
{
    protected string $component = 'cl-m1-button';

    private ?string $label;

    private ?string $href;

    private bool $disabled = false;

    private string $target = '_self';

    private ?string $icon;

    private string $size = 'medium';

    private ?string $title;

    private ?string $ariaLabel;

    private ?string $ariaControls;

    private string $iconAlignment = 'right';

    private CLA2Icon $iconComponent;

    private CLA5Text $textComponent;

    public static function fromArray(array $params): CLM1Button
    {
        $component = new self();

        // Default properties
        $component->className = $params['className'] ?? null;
        $component->id = $params['id'] ?? null;

        // Custom properties
        $component->label = $params['label'] ?? null;
        $component->href = $params['href'] ?? null;
        $component->disabled = $params['disabled'] ?? $component->disabled;
        $component->target = $params['target'] ?? $component->target;
        $component->icon = $params['icon'] ?? null;
        $component->size = $params['size'] ?? $component->size;
        $component->title = $params['title'] ?? null;
        $component->ariaLabel = $params['ariaLabel'] ?? null;
        $component->ariaControls = $params['ariaControls'] ?? null;
        $component->iconAlignment = $params['iconAlignment'] ?? $component->iconAlignment;

        if ($component->icon) $component->iconComponent = CLA2Icon::fromArray([
            'name' => $component->icon,
            'className' => 'button-icon',
        ]);

        if ($component->label) $component->textComponent = CLA5Text::fromArray([
            'copy' => $component->label,
            'className' => 'button-label',
            'style' => $component->size === 'small' ? 'copy-3' : 'copy-1',
            'as' => 'span',
        ]);

        return $component;
    }

    public function rules(): Assert\Collection
    {
        return $this->extendBaseRules([
            'label' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'href' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'disabled' => new Assert\Optional([
                new Assert\Type(['boolean']),
            ]),
            'target' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'icon' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'size' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'title' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'ariaLabel' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'ariaControls' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'iconAlignment' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
        ]);
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAriaLabel(): ?string
    {
        return $this->ariaLabel;
    }

    public function getAriaControls(): ?string
    {
        return $this->ariaControls;
    }

    public function getIconAlignment(): string
    {
        return $this->iconAlignment;
    }

    public function getIconComponent(): CLA2Icon
    {
        return $this->iconComponent;
    }

    public function getTextComponent(): CLA5Text
    {
        return $this->textComponent;
    }

    public function getClassName(): ?array
    {
        $className = is_string($this->className) ? [$this->className] : $this->className;

        if ($this->icon) $className[] = 'icon-alignment-' . $this->iconAlignment;

        $className[] = 'size-' . $this->size;

        return $className;
    }
}