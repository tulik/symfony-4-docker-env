<?php
namespace App\Service\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * UserManager constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->entityManager = $entityManager;
        $this->repository = $userRepository;
    }
    /**
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
    /**
     * @return string
     */
    public function getClass()
    {
        return User::class;
    }
    /**
     * @param array $criteria
     *
     * @return object
     */
    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    /**
     * @return array
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }
    /**
     * @param UserInterface $user
     */
    public function reloadUser(UserInterface $user)
    {
        $this->entityManager->refresh($user);
    }

    /**
     * Finds a user by email.
     *
     * @param string $email
     *
     * @return User|UserInterface|object
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(array('email' => strtolower($email)));
    }
}
