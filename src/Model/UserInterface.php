<?php

namespace JMB\UserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends SymfonyUserInterface
{
    const ROLE_USER = 'ROLE_USER';

    public function setUsername(string $username);

    public function getUsername(): string;

    public function getEmail(): string;

    public function setPlainPassword(string $plainPassword);

    public function getPlainPassword(): ?string;

    public function setPassword(string $password);

    public function setConfirmationToken(string $confirmationToken);

    public function getConfirmationToken(): ?string;

    public function addRole(string $role);

    public function removeRole(string $role);
}
