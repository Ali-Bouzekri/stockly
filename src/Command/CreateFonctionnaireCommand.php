<?php

namespace App\Command;

use App\Entity\Fonctionnaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-fonctionnaire')]
class CreateFonctionnaireCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fonctionnaire = new Fonctionnaire();
        $fonctionnaire->setNom('Admin');
        $fonctionnaire->setPrenom('User');
        $fonctionnaire->setEmail('admin@example.com');
        $fonctionnaire->setPassword($this->passwordHasher->hashPassword($fonctionnaire, 'password'));
        $fonctionnaire->setRoles(['ROLE_USER']);
        $fonctionnaire->setResponsable(true);

        $this->entityManager->persist($fonctionnaire);
        $this->entityManager->flush();

        $output->writeln('✅ Fonctionnaire created: admin@example.com / password');

        return Command::SUCCESS;
    }
}