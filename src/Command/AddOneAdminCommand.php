<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddOneAdminCommand extends Command
{
    protected static $defaultName = 'app:add-one-admin';

    private SymfonyStyle $io;
    private EntityManagerInterface $em;
    private UserPasswordEncoderInterface $encoder;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->entityManager = $em;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Créer un user en base de données')
            ->addArgument('username', InputArgument::REQUIRED, 'Identifiant user')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe user')
            ->addArgument('role', InputArgument::REQUIRED, 'Role user');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->io->section("Ajout d'un user en base de données");
        $this->enterUsername($input, $output);
        $this->enterPassword($input, $output);
        $this->enterRole($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function enterUsername(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');

        $usernameQuestion = new Question("Identifiant User :");
        $usernameQuestion->setValidator([$this->validator, 'validateUsername']);

        $username = $helper->ask($input, $output, $usernameQuestion);

        $input->setArgument('username', $username);
    }

    private function enterPassword(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');

        $passwordQuestion = new Question("Mot de passe User :");
        $passwordQuestion->setValidator([$this->validator, 'validatePassword']);
        $passwordQuestion->setHidden(True)
            ->setHiddenFallback(False);

        $password = $helper->ask($input, $output, $passwordQuestion);

        $input->setArgument('password', $password);
    }

    private function enterRole(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');

        $roleQuestion = new ChoiceQuestion(
            "Sélection du rôle user",
            ['ROLE_USER', 'ROLE_ADMIN'],
            'ROLE_USER'
        );

        $roleQuestion->setErrorMessage('Rôle user invalide');

        $role = $helper->ask($input, $output, $roleQuestion);

        $input->setArgument('role', $role);
    }
}
