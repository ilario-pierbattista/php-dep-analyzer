<?php

namespace Pybatt\PhpDepAnalysis\Symbol;

abstract class AbstractSymbol implements SymbolInterface, \JsonSerializable
{
    private array $dependencies = [];

    public function getKey(): string
    {
        return $this->getName();
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function addDependency(string $name): void
    {
        if (! in_array($name, $this->dependencies, true)) {
            $this->dependencies[] = $name;
        }
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->getName(),
            'dependencies' => $this->getDependencies(),
            'type' => $this->getType(),
        ];
    }
}
