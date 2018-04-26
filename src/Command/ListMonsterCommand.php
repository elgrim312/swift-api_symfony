<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 23/04/18
 * Time: 16:29
 */

namespace App\Command;


use App\Entity\Monster;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListMonsterCommand extends Command
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName("app:list_monster");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io  = new SymfonyStyle($input, $output);
        $mosnterManager = $this->em->getRepository(Monster::class);
        $monsters = $mosnterManager->findAll();


        foreach ($monsters as $monster) {
            $io->writeln(sprintf("<info>Nom monstre %i : </info> %s", $monster->getId(), $monster->getName()));
        }

    }
}