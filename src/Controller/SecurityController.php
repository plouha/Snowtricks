<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/security/", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login'); // Après inscription on est redirigé sur le formulaire de login
        }
        
        return $this->render('security/registration.html.twig', [ // Pour s'inscrire on est envoyé sur le formulaire correspondant
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils) { // Formulaire de connexion avec contrôle d'authentification

        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom entré
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
        
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {

    }
}
