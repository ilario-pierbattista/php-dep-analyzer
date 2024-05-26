<?php

namespace Pybatt\PhpDepAnalysis\Parser;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use Pybatt\PhpDepAnalysis\Reader\CodeReaderInterface;
use Pybatt\PhpDepAnalysis\Symbol\SymbolInterface;

class DependenciesParser
{
    private array $symbolsTable = [];

    public function __construct(
        private readonly Parser $parser
    ) {}

    public function parse(CodeReaderInterface $reader): void
    {
        $ast = $this->parser->parse($reader->read());

        // Resolve names
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->traverse($ast);

        $symbolVisitor = new SymbolVistor();
        $traverser->addVisitor($symbolVisitor);

        $traverser->traverse($ast);

        foreach ($symbolVisitor->getSymbols() as $symbol) {
            $this->addSymbol($symbol);
        }
    }

    private function addSymbol(SymbolInterface $symbol): void
    {
        if (! array_key_exists($symbol->getKey(), $this->symbolsTable)) {
            $this->symbolsTable[] = $symbol;
        }
    }

    public function getSymbolsTable(): array
    {
        return $this->symbolsTable;
    }
}
