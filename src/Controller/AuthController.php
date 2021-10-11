<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/auth")
 */
class AuthController extends AbstractController
{

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();

        $user = $serializer->deserialize($data, User::class, 'json');

        $date = new \Datetime();
        $user->setCreatedAt($date);

        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();

        return $this->json($user, 200);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response { return $this->render("auth/index.html.twig"); }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response { }
}
