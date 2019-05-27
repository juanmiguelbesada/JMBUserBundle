<?php

namespace JMB\UserBundle\Controller;

use JMB\UserBundle\Form\Type\ForgotPasswordFormType;
use JMB\UserBundle\Form\Type\ResetPasswordFormType;
use JMB\UserBundle\Mailer\MailerInterface;
use JMB\UserBundle\Manager\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotPasswordController extends AbstractController
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var array
     */
    private $parameters;

    /**
     * ForgotPasswordController constructor.
     *
     * @param UserManagerInterface $userManager
     * @param MailerInterface      $mailer
     * @param TranslatorInterface  $translator
     * @param array                $parameters
     */
    public function __construct(UserManagerInterface $userManager, MailerInterface $mailer, TranslatorInterface $translator, array $parameters = [])
    {
        $defaultParameters = [
            'login_route' => 'jms_user_login',
            'forgot_password_template' => '@JMBUser/forgot_password/forgot_password.html.twig',
            'reset_password_template' => '@JMBUser/forgot_password/reset_password.html.twig',
        ];

        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->parameters = array_merge($defaultParameters, $parameters);
    }

    public function forgotPassword(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->userManager->findUserByEmail($email);

            //We can asume $user always exists, as ForgotPasswordFormType
            //contains a constraint that validates the email exists

            //Set a random generated token
            $user->setConfirmationToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));

            //Save user changes
            $this->userManager->updateUser($user);

            //Send reset email
            $this->mailer->sendResetPasswordEmailMessage($user);

            $this->addFlash('success', $this->translator->trans('message.forgot_password_email_sent', ['%email%' => $email], 'JMBUserBundle'));
        }

        return $this->render($this->parameters['forgot_password_template'], [
            'form' => $form->createView(),
        ]);
    }

    public function resetPassword(Request $request, string $token): Response
    {
        if (!$token) {
            return $this->redirectToRoute($this->parameters['login_route']);
        }

        $user = $this->userManager->findUserByConfirmationToken($token);

        //User not found
        if (null === $user) {
            $this->addFlash('danger', $this->translator->trans('error.invalid_forgot_password_confirmation_token', [], 'JMBUserBundle'));

            return $this->redirectToRoute($this->parameters['login_route']);
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $user->setPlainPassword($newPassword);
            $user->setConfirmationToken(null);
            $this->userManager->updateUser($user);

            $this->addFlash('success', $this->translator->trans('message.password_reset_successfully', [], 'JMBUserBundle'));

            return $this->redirectToRoute($this->parameters['login_route']);
        }

        return $this->render($this->parameters['reset_password_template'], [
            'form' => $form->createView(),
        ]);
    }
}
