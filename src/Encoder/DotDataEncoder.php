<?php

namespace Pybatt\PhpDepAnalysis\Encoder;

use Pybatt\PhpDepAnalysis\Symbol\SymbolInterface;

class DotDataEncoder
{
    /**
     * @param SymbolInterface[] $symbols
     * @return string
     */
    public static function encode(array $symbols): string
    {
        $arcs = [];
        foreach ($symbols as $symbol) {
            foreach ($symbol->getDependencies() as $dependency) {
                $arcs[] = sprintf('"%s" -> "%s"', self::escape($symbol->getKey()), self::escape($dependency));
            }
        }

        $nodes = [];
        foreach ($symbols as $symbol) {
            if(! in_array($symbol->getKey(), $nodes, true)) {
                $nodes[] = $symbol->getKey();
            }
            foreach ($symbol->getDependencies() as $dependency) {
                if(! in_array($dependency, $nodes, true)) {
                    $nodes[] = $dependency;
                }
            }
        }

        $arcs = implode("\n", $arcs);

        $rootSubGraph = self::rootSubGraph($nodes);
        $partSubGraph = self::partSubGraph($nodes);


        $dot = <<<DOT
digraph G {  
node [ordering="out"]
graph [layout="dot"]
 
$rootSubGraph
$partSubGraph

$arcs
}
DOT;
        return $dot;
    }

    /**
     * @param string[] $nodes
     * @return string
     */
    private static function rootSubGraph(array $nodes): string
    {
        $root = [];
        foreach ($nodes as $node) {
            $parts = explode("\\", $node);
            if(count($parts) === 1) {
                $root[] = $node;
            }
        }

        $elements = implode(
            "\n",
            array_map(
                fn(string $x) => "\"$x\";",
                $root
            )
        );

        return self::clusterTemplate("root", $elements);
    }

    private static function partSubGraph(array $nodes): string
    {
        $splitted = [];
        foreach ($nodes as $node) {
            $parts = explode("\\", $node);
            if(count($parts) < 2) {
                continue;
            }
            $splitted[$node] = array_slice($parts, 0, -1);
        }

        $tree = [];
        foreach ($splitted as $name => $parts) {
            self::appendArray($tree, $parts, $name);
        }

        return self::subgraphFromTree($tree, '');
    }

    private static function subgraphFromTree(array $tree, string $k): string
    {
        var_dump($k);
        $nodes = array_filter(
            $tree,
            fn(mixed $x) => is_array($x)
        );
        $subgraphs = [];

        foreach ($nodes as $key => $node) {
            $subgraphs[] = self::subgraphFromTree($node, $k . "\\" . $key);
        }

        $leaves = array_filter(
            $tree,
            fn(mixed $x) => ! is_array($x)
        );

        if(empty($k)) {
            return implode("\n", $subgraphs);
        }


//        var_dump('LEAVES');
//        var_dump($leaves);

        $content = sprintf(
            "%s\n%s",
            implode("\n", $subgraphs),
            implode("\n", array_map(fn(string $x) => self::elementInCluster($x), $leaves))
        );

        return self::clusterTemplate($k, $content);
    }

    private static function elementInCluster(string $x): string
    {
        return sprintf('"%s";', self::escape($x));
    }

    private static function clusterTemplate(string $name, string $content): string
    {
        $n = self::escape($name);
        return <<<DOT
subgraph "$n" {
    cluster=true;
    label="$n"
    color=blue
    $content
}
DOT;
    }

    private static function appendArray(array &$data, array $path, mixed $value): void
    {
        $p = array_shift($path);

        if(empty($path)) {
            $data[$p][] = $value;
            return;
        }

        if(!isset($data[$p])) {
            $data[$p] = [];
        }

        self::appendArray($data[$p], $path, $value);
    }

    private static function escape(string $name): string {
        return str_replace("\\", "\\\\", $name);
    }
}