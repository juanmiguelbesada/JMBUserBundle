<?php

namespace JMB\UserBundle\Manager;

use JMB\UserBundle\Model\UserInterface;

interface UserManagerInterface
{
    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findUserByUsername(string $username): ?UserInterface;

    /**
     * Find a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function findUserByEmail(string $email): ?UserInterface;

    /**
     * Find a user by its username or email.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findUserByUsernameOrEmail(string $username): ?UserInterface;

    /**
     * Find a user by its confirmation token.
     *
     * @param string $confirmationToken
     *
     * @return UserInterface|null
     */
    public function findUserByConfirmationToken(string $confirmationToken): ?UserInterface;

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param bool          $andFlush
     */
    public function updateUser(UserInterface $user, $andFlush = true);

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     */
    public function updatePassword(UserInterface $user);
}
