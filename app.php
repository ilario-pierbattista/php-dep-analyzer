#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands
$application->add(new \Pybatt\PhpDepAnalysis\Command\AnalyseCommand(__DIR__));

$application->run();
