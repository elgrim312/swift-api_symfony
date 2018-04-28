<?php

namespace App\Command;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppCreateEventCommand extends Command
{
    protected static $defaultName = 'app:create-event';

    private $eventRp;
    private $locationRp;
    private $em;

    public function __construct(LocationRepository $locationRepository, EventRepository $eventRepository, ObjectManager $entity)
    {
        parent::__construct();
        $this->eventRp = $eventRepository;
        $this->locationRp = $locationRepository;
        $this->em = $entity;

    }

    protected function configure()
    {
        $this
            ->setDescription('create a event')
            ->addArgument('location_id', InputArgument::REQUIRED, 'Is ID of location')
            ->addArgument('name', InputArgument::REQUIRED, 'Is name of location')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('location_id');
        $name = $input->getArgument('name');

        $location = $this->locationRp->find($locationId);
        $event = new Event();

        $event->setName($name);
        $event->setLocation($location);
        $event->setStartAt(new \DateTime());

        $this->em->persist($event);
        $this->em->flush();

        $io->success('Congratulation new event is create');
    }
}
