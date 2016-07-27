<?php

namespace MS\Sentry\Monitor\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @package MS\Sentry\Monitor\Command
 */
class RunCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('run sentry monitor server')
            ->addArgument('address', InputArgument::OPTIONAL, 'Address:port', 'localhost:8006')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Start and run sentry monitor');

        $address = $input->getArgument('address');
        $webPath = __DIR__ . '/../../web';

        $processBuilder = new ProcessBuilder([PHP_BINARY, '-S', $address, $webPath . '/index.php']);
        $processBuilder
            ->setWorkingDirectory($webPath . '/scripts')
            ->setTimeout(null);

        $io->success(sprintf('Server running on "%s"', $address));
        $io->comment('Quit the server with CONTROL-C.');

        $process = $processBuilder->getProcess();
        $process->run();
    }
}
