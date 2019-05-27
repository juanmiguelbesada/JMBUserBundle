<?php

namespace JMB\UserBundle\Controller;

use JMB\UserBundle\Form\Type\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * LoginController constructor.
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param array               $parameters
     */
    public function __construct(AuthenticationUtils $authenticationUtils, array $parameters = [])
    {
        $defaultParameters = [
            'login_template' => '@JMBUser/login/login.html.twig',
        ];
        $this->authenticationUtils = $authenticationUtils;
        $this->parameters = array_merge($defaultParameters, $parameters);
    }

    public function login(): Response
    {
        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $usernameOrEmail = $this->authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class, ['_username' => $usernameOrEmail]);

        return $this->render($this->parameters['login_template'], [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
