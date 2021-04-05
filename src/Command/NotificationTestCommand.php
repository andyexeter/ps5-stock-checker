<?php

namespace App\Command;

use App\Component\SiteInterface;
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

class NotificationTestCommand extends Command
{
    protected static $defaultName = 'app:test';
    private NotifierInterface $notifier;
    private string $emailRecipient;

    public function __construct(NotifierInterface $notifier, string $emailRecipient)
    {
        parent::__construct();
        $this->notifier       = $notifier;
        $this->emailRecipient = $emailRecipient;
    }

    protected function configure()
    {
        $this->setDescription('Sends a test notification');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$this->emailRecipient) {
            $io->error('Email recipient cannot be blank');
            return Command::FAILURE;
        }

        $notification = (new Notification('TEST: PS5 Stock Checker', ['email']))
            ->importance(Notification::IMPORTANCE_LOW)
            ->content('This is a test notification to ensure the PS5 stock checker is working');

        $this->notifier->send($notification, new Recipient($this->emailRecipient));

        $io->success('Test notification sent');

        return Command::SUCCESS;
    }
}
