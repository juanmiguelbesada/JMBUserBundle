<?php

namespace JMB\UserBundle\Mailer;

use JMB\UserBundle\Model\UserInterface;

interface MailerInterface
{
    /**
     * Send an email to a user to confirm the password reset.
     *
     * @param UserInterface $user
     * @param array         $parameters
     */
    public function sendResetPasswordEmailMessage(UserInterface $user, array $parameters = []);
}
