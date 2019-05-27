<?php

namespace JMB\UserBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMB\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager implements UserManagerInterface
{
    /**
     * @var ObjectManager
     */
    public $objectManager;

    /**
     * @var string
     */
    public $class;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * UserManager constructor.
     *
     * @param ObjectManager           $objectManager
     * @param string                  $class
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(ObjectManager $objectManager, string $class, EncoderFactoryInterface $encoderFactory)
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername(string $username): ?UserInterface
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            $user = $this->findUserByEmail($usernameOrEmail);
            if (null !== $user) {
                return $user;
            }
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByConfirmationToken(string $confirmationToken): ?UserInterface
    {
        return $this->findOneBy(['confirmationToken' => $confirmationToken]);
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updatePassword($user);
        $this->objectManager->persist($user);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(UserInterface $user)
    {
        if (null === $user->getPlainPassword() || 0 === strlen($user->getPlainPassword())) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->objectManager->getRepository($this->class);
    }

    /**
     * @param array $criteria
     *
     * @return UserInterface|null
     */
    protected function findOneBy(array $criteria): ?UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->getRepository()->findOneBy($criteria);

        return $user;
    }
}
