<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Component;

use Symfony\Component\Validator\Constraints\Collection;

interface ComponentInterface
{
    function getComponent(): string;

    public static function fromObject(object $params): ComponentInterface;

    public function rules(): Collection;
}