<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            ->addArgument('plainpassword', InputArgument::REQUIRED, 'Mot de passe user')
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
        /** @var string $username */
        $username = $input->getArgument('username');

        /** @var string $plainPassword */
        $plainPassword = $input->getArgument('plainPassword');

        /** @var string $role */
        $role = [$input->getArgument('role')];

        $user = new User();

        $user->setUsername($username)
            ->setPassword($this->encoder->encodePassword($user, $plainPassword))
            ->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->io->success("Un nouvel utilisateur est inscrit en base de données");

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

        $input->setArgument('plainpassword', $password);
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
