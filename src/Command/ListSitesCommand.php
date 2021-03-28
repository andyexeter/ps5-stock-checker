<?php

namespace App\Command;

use App\Component\Site;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class ListSitesCommand extends Command
{
    protected static $defaultName = 'app:list';
    /** @var array<Site> */
    private array $sites;

    public function __construct(iterable $sites)
    {
        parent::__construct();
        foreach ($sites as $site) {
            $this->sites[] = $site;
        }
    }

    protected function configure()
    {
        $this->setDescription('Lists all registered sites to check');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->table(['Name', 'FQCN'], array_map(fn(Site $site) => [$site->getName(), get_class($site)], $this->sites));

        return Command::SUCCESS;
    }
}
