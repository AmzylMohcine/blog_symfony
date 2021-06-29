<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription" , name= "security")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher)
    {

        $user = new User();

        $formRegister = $this->createForm(RegisterType::class, $user);
        $formRegister->handleRequest($request);

        if ($formRegister->isSubmitted() && $formRegister->isValid()) {

            $hash = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush($user);

            return $this->redirectToRoute('connexion');
        }

        return $this->render('security/register.html.twig', [
            'formRegister' => $formRegister->createView(),
        ]);
    }

    /**
     * @Route("/connexion" , name= "connexion")
     */

    public function login()
    {

        return $this->render('security/login.html.twig');
    }
    /**
     * @Route("/deconnexion" , name= "deconnexion")
     */

    public function logout()
    {

        return $this->render('security/login.html.twig');
    }
}
