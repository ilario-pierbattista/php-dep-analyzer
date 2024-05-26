<?php

namespace Pybatt\PhpDepAnalysis\Symbol;

class InterfaceSymbol extends AbstractSymbol
{
    public function __construct(
        public readonly string $fqcn
    ) {}

    public function getName(): string
    {
        return $this->fqcn;
    }

    public function getType(): SymbolTypeEnum
    {
        return SymbolTypeEnum::SInterface;
    }
}
