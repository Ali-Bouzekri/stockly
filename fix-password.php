<?php

require __DIR__.'/vendor/autoload.php';

use App\Entity\Fonctionnaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();

$em = $container->get(EntityManagerInterface::class);
$hasher = $container->get(UserPasswordHasherInterface::class);

$user = $em->getRepository(Fonctionnaire::class)->findOneBy(['email' => 'admin@example.com']);

if (!$user) {
    echo "User not found.\n";
    exit(1);
}

$hashedPassword = $hasher->hashPassword($user, 'password');
$user->setPassword($hashedPassword);
$em->flush();

echo "Password hashed successfully. New hash: " . $hashedPassword . "\n";