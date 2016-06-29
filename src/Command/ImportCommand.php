<?php

namespace EnlightenedDC\Sentry\Monitor\Command;

use EnlightenedDC\Sentry\Monitor\Application;
use EnlightenedDC\Sentry\Monitor\Model\SentryRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @package EnlightenedDC\Sentry\Monitor\Command
 */
class ImportCommand extends Command
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @param Application $application
     * @param string      $name
     */
    public function __construct(Application $application, $name = null)
    {
        parent::__construct($name);

        $this->application = $application;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('import')
            ->setDescription('import sentry events by organisation/project')
            ->addArgument('organisation', InputArgument::REQUIRED, 'slug of the organisation')
            ->addArgument('project', InputArgument::OPTIONAL, 'slug of the project, if empty, all projects will be imported')
            ->addOption('sentry-url', 's', InputOption::VALUE_REQUIRED, 'base sentry url')
            ->addOption('sentry-api-key', 'a', InputOption::VALUE_REQUIRED, 'sentry api key')
            ->addOption('project-blacklist', 'b', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'blacklist specific projects', [])
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
        $io->title('Import sentry events');

        $organisation = $input->getArgument('organisation');
        $project = $input->getArgument('project');
        $projectBlacklist = $input->getOption('project-blacklist');
        $sentryRequest = new SentryRequest($input->getOption('sentry-url'), $input->getOption('sentry-api-key'));

        $projects = [$project];

        if (null === $project) {
            $projects = $this->application['project.collector']->getSlugs($sentryRequest, $organisation);
        }

        $progress = new ProgressBar($output);
        $progress->start();

        foreach ($projects as $project) {
            if (in_array($project, $projectBlacklist)) {
                continue;
            }

            $simplifiedEvents = $this->application['event.collector']->getSimplifiedEvents($sentryRequest, $organisation, $project);

            foreach ($simplifiedEvents as $simplifiedEvent) {
                $this->application['importer']->import($organisation, $project, $simplifiedEvent);
                $progress->advance();
            }
        }

        $progress->finish();

        $io->newLine(2);
        $eventCount = $this->application['db']->fetchColumn('SELECT COUNT(*) FROM events');
        $io->success(sprintf('%s events are available.', $eventCount));
    }
}
