<?php

namespace Pybatt\PhpDepAnalysis\Parser;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\NodeVisitorAbstract;
use Pybatt\PhpDepAnalysis\Symbol\ClassSymbol;
use Pybatt\PhpDepAnalysis\Symbol\FunctionSymbol;
use Pybatt\PhpDepAnalysis\Symbol\InterfaceSymbol;
use Pybatt\PhpDepAnalysis\Symbol\SymbolInterface;
use Pybatt\PhpDepAnalysis\Symbol\SymbolTypeEnum;
use Pybatt\PhpDepAnalysis\Symbol\TraitSymbol;

class SymbolVistor extends NodeVisitorAbstract
{
    /** @var SymbolInterface[] */
    private array $symbols = [];

    private ?SymbolInterface $currentParentSymbol = null;

    public function enterNode(Node $node)
    {
        switch ($this->currentParentSymbol?->getType()) {
            case null:
                $this->baseNodeVisiter($node);
                break;
            case SymbolTypeEnum::SClass:
            case SymbolTypeEnum::SInterface:
            case SymbolTypeEnum::SFunction:
            case SymbolTypeEnum::STrait:
                $this->dependenciesNodeVisiter($node);
                break;
        }
    }

    private function baseNodeVisiter(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            $traits = [];
            foreach ($node->getTraitUses() as $traitUse) {
                $traits = array_merge(
                    $traits,
                    $traitUse->traits
                );
            }

            $classSymbol = new ClassSymbol(
                $node->namespacedName,
                $node->extends?->name,
                array_map(
                    static fn(Name $n): string => $n->name,
                    $node->implements
                ),
                array_map(
                    static fn(Name $t): string => $t->name,
                    $traits
                )
            );

            $this->currentParentSymbol = $classSymbol;
            $this->symbols[] = $classSymbol;
        }

        if ($node instanceof Node\Stmt\Interface_) {
            $interfaceSymbol = new InterfaceSymbol($node->namespacedName);
            $this->currentParentSymbol = $interfaceSymbol;
            $this->symbols[] = $interfaceSymbol;
        }

        if ($node instanceof Node\Stmt\Function_) {
            $functionSymbol = new FunctionSymbol($node->namespacedName);
            $this->currentParentSymbol = $functionSymbol;
            $this->symbols[] = $functionSymbol;
        }

        if ($node instanceof Node\Stmt\Trait_) {
            $traitSymbol = new TraitSymbol($node->namespacedName);
            $this->currentParentSymbol = $traitSymbol;
            $this->symbols[] = $traitSymbol;
        }
    }

    private function dependenciesNodeVisiter(Node $node): void
    {
        if ($node instanceof Name\FullyQualified) {
            $this->currentParentSymbol?->addDependency($node->name);
        }
    }

    /**
     * @return SymbolInterface[]
     */
    public function getSymbols(): array
    {
        return $this->symbols;
    }
}
