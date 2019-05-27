<?php

namespace JMB\UserBundle\Controller;

use JMB\UserBundle\Form\Type\ChangePasswordFormType;
use JMB\UserBundle\Mailer\MailerInterface;
use JMB\UserBundle\Manager\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController extends AbstractController
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
    protected $parameters;

    /**
     * ProfileController constructor.
     *
     * @param UserManagerInterface $userManager
     * @param MailerInterface      $mailer
     * @param TranslatorInterface  $translator
     * @param array                $parameters
     */
    public function __construct(UserManagerInterface $userManager, MailerInterface $mailer, TranslatorInterface $translator, array $parameters = [])
    {
        $defaultParameters = [
            'login_route' => 'jmb_user_login',
            'overview_template' => '@JMBUser/profile/overview.html.twig',
            'change_password_template' => '@JMBUser/profile/change_password.html.twig',
        ];

        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->parameters = array_merge($defaultParameters, $parameters);
    }

    public function index(): Response
    {
        return $this->forward('jmb_user.controller.profile:overview');
    }

    public function overview(): Response
    {
        return $this->render($this->parameters['overview_template']);
    }

    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();
        if (null === $user) {
            return $this->redirectToRoute($this->parameters['login_route']);
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $user->setPlainPassword($newPassword);
            $this->userManager->updateUser($user);

            $this->addFlash('success', $this->translator->trans('message.password_changed_successfully', [], 'JMBUserBundle'));
        }

        return $this->render($this->parameters['change_password_template'], [
            'form' => $form->createView(),
        ]);
    }
}
