<?php declare(strict_types=1);

Testbench\Bootstrap::setup(TMP_DIR, function (\Nette\Configurator $configurator) {
    $configurator->createRobotLoader()->addDirectory([
        __DIR__ . '/../src',
    ])->register();

    $configurator->addParameters([
        'appDir' => __DIR__ . '/../src',
    ]);
});