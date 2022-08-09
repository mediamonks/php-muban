<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Component\Library;

use MediaMonks\Muban\Component\ComponentInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractComponent implements ComponentInterface
{
    protected array|string|null $className;

    protected ?string $id;

    public function getComponent(): string
    {
        return $this->component;
    }

    public function extendBaseRules(array $rules): Assert\Collection
    {
        return new Assert\Collection([
            'className' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
            'id' => new Assert\Optional([
                new Assert\NotBlank(),
            ]),
        ] + $rules);
    }


    public function getClassName(): array
    {
        return is_string($this->className) ? [$this->className] : $this->className;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}