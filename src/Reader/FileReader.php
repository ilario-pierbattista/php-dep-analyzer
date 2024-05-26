<?php

namespace Pybatt\PhpDepAnalysis\Reader;

class FileReader implements CodeReaderInterface
{
    public function __construct(
        public readonly string $filePath
    ) {}

    public function read(): string
    {
        return file_get_contents($this->filePath);
    }

    /**
     * @return self[]
     */
    public static function generateReadersRecursively(string $path): array
    {
        $files = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $item) {
            if ($item->isFile() && $item->getExtension() === 'php') {
                $files[] = new self($item->getRealPath());
            }
        }

        return $files;
    }
}
