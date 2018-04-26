<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppSurnameCommand extends Command
{
    protected static $defaultName = 'app:SurnameCommand';
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $question = new Question(" Nom ?". PHP_EOL, false);

        $name = $helper->ask($input, $output, $question);

        if (!$name) {
           return;
       }

        $this->logger->info(sprintf("Name %s", $name));
        $io->success("Nom :" . $name);
    }
}
