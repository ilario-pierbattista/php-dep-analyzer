<?php

namespace Pybatt\PhpDepAnalysis\Symbol;

class ClassSymbol extends AbstractSymbol
{
    public function __construct(
        public readonly string $fqcn,
        public readonly ?string $extends = null,
        public readonly array $implements = [],
        public readonly array $traitUses = []
    ) {}

    public function getName(): string
    {
        return $this->fqcn;
    }

    public function getType(): SymbolTypeEnum
    {
        return SymbolTypeEnum::SClass;
    }

    public function jsonSerialize(): mixed
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'extends' => $this->extends,
                'implements' => $this->implements,
                'trait_uses' => $this->traitUses,
            ]
        );
    }
}
