<?php

namespace Pybatt\PhpDepAnalysis\Encoder;

use Pybatt\PhpDepAnalysis\Symbol\SymbolInterface;

class GraphDataEncoder
{
    public static function encode(array $symbols): array
    {
        return [
            'nodes' => self::encodeNodes($symbols),
            'links' => self::encodeLinks($symbols),
        ];
    }

    /**
     * @param SymbolInterface[] $symbols
     *
     * @return list<array{id: string, name: string}>
     */
    private static function encodeNodes(array $symbols): array
    {
        $uniqueNodes = [];

        foreach ($symbols as $symbol) {
            if (! array_key_exists($symbol->getKey(), $uniqueNodes)) {
                $uniqueNodes[$symbol->getKey()] = [
                    'id' => $symbol->getKey(),
                    'name' => $symbol->getName(),
                ];
            }
        }

        foreach ($symbols as $symbol) {
            foreach ($symbol->getDependencies() as $dependency) {
                if (! array_key_exists($dependency, $uniqueNodes)) {
                    $uniqueNodes[$dependency] = [
                        'id' => $dependency,
                        'name' => $dependency,
                    ];
                }
            }
        }

        return array_values($uniqueNodes);
    }

    /**
     * @param SymbolInterface[] $symbols
     *
     * @return list<array{source: string, target: string}>
     */
    private static function encodeLinks(array $symbols)
    {
        $links = [];
        foreach ($symbols as $symbol) {
            foreach ($symbol->getDependencies() as $dependency) {
                $links[] = [
                    'source' => $symbol->getKey(),
                    'target' => $dependency,
                ];
            }
        }

        return $links;
    }
}
