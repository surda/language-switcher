<?php declare(strict_types=1);

namespace Surda\LanguageSwitcher;

interface LanguageSwitcherFactory
{
    /**
     * @return LanguageSwitcherControl
     */
    public function create(): LanguageSwitcherControl;
}
