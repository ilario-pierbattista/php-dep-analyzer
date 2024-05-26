<?php

namespace Pybatt\PhpDepAnalysis\Command;

use PhpParser\ParserFactory;
use Pybatt\PhpDepAnalysis\Encoder\DotDataEncoder;
use Pybatt\PhpDepAnalysis\Encoder\GraphDataEncoder;
use Pybatt\PhpDepAnalysis\Parser\DependenciesParser;
use Pybatt\PhpDepAnalysis\Reader\FileReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyseCommand extends Command
{
    private const ARG_path = 'path';

    public function __construct(
        private readonly string $rootDir
    ) {
        parent::__construct(
            'php-dep:analyse'
        );
    }

    protected function configure()
    {
        $this->addArgument(self::ARG_path, InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument(self::ARG_path);
        $readers = FileReader::generateReadersRecursively($path);

        $output->writeln(
            sprintf(
                'Found %d files',
                count($readers)
            )
        );

        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $depParser = new DependenciesParser($parser);

        foreach ($readers as $reader) {
            $depParser->parse($reader);
        }

        $data = GraphDataEncoder::encode($depParser->getSymbolsTable());
        $output->writeln(json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

        //        $data = json_encode([
        //            'd' => $depParser->getSymbolsTable()
        //        ], JSON_THROW_ON_ERROR);

        $outdir = $this->rootDir . DIRECTORY_SEPARATOR . 'output';
        if (! file_exists($outdir)) {
            mkdir($outdir, 0o777, true);
        }
        file_put_contents($outdir . DIRECTORY_SEPARATOR . 'data.json', json_encode($data, JSON_PRETTY_PRINT));

        $dot = DotDataEncoder::encode($depParser->getSymbolsTable());
        file_put_contents($outdir . DIRECTORY_SEPARATOR . 'data.dot', $dot);

        return 0;
    }
}
