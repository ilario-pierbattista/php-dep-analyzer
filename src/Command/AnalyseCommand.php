<?php

namespace Pybatt\PhpDepAnalysis\Command;

use PhpParser\ParserFactory;
use Pybatt\PhpDepAnalysis\Parser\DependenciesParser;
use Pybatt\PhpDepAnalysis\Reader\FileReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyseCommand extends Command
{
    private const ARG_path = 'path';

    public function __construct()
    {
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

        $output->writeln(
            json_encode($depParser->getSymbolsTable(), JSON_PRETTY_PRINT)
        );

        return 0;
    }
}
