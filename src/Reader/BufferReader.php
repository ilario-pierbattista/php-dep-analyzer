<?php

namespace Pybatt\PhpDepAnalysis\Reader;

class BufferReader implements CodeReaderInterface
{
    public function __construct(public readonly string $buffer) {}

    public function read(): string
    {
        return $this->buffer;
    }
}
