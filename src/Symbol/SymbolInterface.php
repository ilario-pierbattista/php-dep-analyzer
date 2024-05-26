<?php

namespace Pybatt\PhpDepAnalysis\Symbol;

use Pybatt\PhpDepAnalysis\Indexable;

interface SymbolInterface extends Indexable
{
    public function getName(): string;

    public function getType(): SymbolTypeEnum;

    /**
     * @return string[]
     */
    public function getDependencies(): array;
    public function addDependency(string $name): void;
}
