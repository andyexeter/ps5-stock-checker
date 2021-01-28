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

    public function __construct(iterable $sites, NotifierInterface $notifier)
    {
        parent::__construct();
        $this->sites = $sites;
        $this->notifier = $notifier;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->sites as $site) {
            $io->write('Checking ' . $site->getName() . '...');

            if ($site->hasChanged()) {
                $io->writeln('Changed. Sending notification');
                $notification = (new Notification('PS5 Stock Alert: ' . $site->getName(), ['email']));
                $notification->content(
                    $site->getName() . ' may have PS5 stock. URL ' . $site->getProductUrl()
                );

                $this->notifier->send($notification, new Recipient('andy@andypalmer.me'));
            } else {
                $io->writeln('No change');
            }
        }

        return Command::SUCCESS;
    }
}
