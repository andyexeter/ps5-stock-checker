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

class ProcessCommand extends Command
{
    protected static $defaultName = 'app:process';
    /** @var iterable<Site> */
    private iterable $sites;
    private NotifierInterface $notifier;
    private string $emailRecipient;

    public function __construct(iterable $sites, NotifierInterface $notifier, string $emailRecipient)
    {
        parent::__construct();
        $this->sites          = $sites;
        $this->notifier       = $notifier;
        $this->emailRecipient = $emailRecipient;
    }

    protected function configure()
    {
        $this
            ->setDescription('Runs the stock checker')
            ->addArgument('sites', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Space separated list of sites to check')
            ->addOption('exclude', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sites to exclude');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sitesToInclude = $input->getArgument('sites');
        $sitesToExclude = $input->getOption('exclude');

        foreach ($this->sites as $site) {
            if ($sitesToInclude && !\in_array($site->getName(), $sitesToInclude)) {
                continue;
            }

            if (\in_array($site->getName(), $sitesToExclude)) {
                continue;
            }

            $io->write('Checking ' . $site->getName() . '...');

            if ($site->hasChanged()) {
                $io->writeln('Changed. Sending notification');
                $notification = (new Notification('PS5 Stock Alert: ' . $site->getName(), ['email']));
                $notification->content(
                    $site->getName() . ' may have PS5 stock. URL ' . $site->getProductUrl()
                );

                $this->notifier->send($notification, new Recipient($this->emailRecipient));
            } else {
                $io->writeln('No change');
            }
        }

        return Command::SUCCESS;
    }
}
