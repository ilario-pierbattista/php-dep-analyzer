<?php

namespace Pybatt\PhpDepAnalysis\Symbol;

enum SymbolTypeEnum implements \JsonSerializable
{
    case SInterface;
    case SClass;
    case SFunction;
    case STrait;

    public function jsonSerialize(): mixed
    {
        return match ($this) {
            self::SClass => 'class',
            self::SInterface => 'interface',
            self::SFunction => 'function',
            self::STrait => 'trait'
        };
    }
}
