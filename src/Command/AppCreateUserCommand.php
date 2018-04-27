<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppCreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    private $userRp;
    private $encoder;
    private $em;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder, ObjectManager $entity)
    {
        parent::__construct();
        $this->userRp = $userRepository;
        $this->encoder = $encoder;
        $this->em = $entity;

    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::REQUIRED, 'email of user')
            ->addArgument('name', InputArgument::REQUIRED, 'name of user')
            ->addArgument('password', InputArgument::REQUIRED, 'password of user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $date = new \DateTime();
        $user->setApiKey(uniqid().$date->getTimestamp());

        $plainpassword = $password;
        $encoded = $this->encoder->encodePassword($user, $plainpassword);
        $user->setPassword($encoded);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
