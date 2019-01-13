<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Upload;
use App\Entity\UploadedFile;
use App\Service\FileUploader;
use App\Form\RegistrationType;
use App\Form\RecoveryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    
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
     * @Route("/security/recover", name="password_recover")
     */
     public function passwordRecover (AuthenticationUtils $authenticationUtils, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        
        $form = $this->createForm(RecoveryType::class)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $email = $form->getData()["email"];          // on récupère le contenu du champ email

            $user = $this   ->getDoctrine()        // on recherche en base l'utilisateur avec ce mail
                            ->getRepository(User::class)
                            ->findOneByEmail($email);
          
            if (!$user) {             // S'il n'y a pas d'utilisateur avec ce mail -> message d'erreur
                $this->addFlash(
                    'ATTENTION ! ',
                    'Ce mail est inconnu !'
                );
                
                return $this->redirectToRoute('password_recover');
                
            } else {                      // sinon
                 

                $code = mt_rand(10000000,20000000);     // méthode PHP -> nombre aléatoire à 8 chiffres (mini, maxi)
 
                $user ->setPassword($code);
                
                $hash = $encoder->encodePassword($user, $user->getPassword());  // on encode le password
                $user->setPassword($hash);
    
                $manager->flush();
           
                // Envoi du mail avec le nouveau mot de passe en clair
                $message = ( new \Swift_Message ());
 
                    $message->setSubject('Nouveau mot de passe');
                    $message->setFrom('bernard-germain5@orange.fr');
                    $message->setTo('plouha57@gmail.com');
                    $message->setBody($this->renderView('security/sendemail.html.twig', array('code' => $code)),'text/html');
 
                    $this->get('mailer')->send($message);
            
                $this->addFlash(                // message flash indique envoi d'un mail avec nouveau mot de passe
                    'Confirmation : ',
                    'Un nouveau password a été envoyé dans votre boite mail !'
                );

                return $this->redirectToRoute('security_login'); // Après envoi du code on est redirigé sur le formulaire de login
            }                
        }

        return $this->render('security/passwordRecover.html.twig', [    // Fichier pour renouveler le password oublié 
            'form' => $form->createView(),                              // On crée la vue du formulaire
        ]);
     }
    
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader) {
        
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($hash);
            $fileName = $fileUploader->upload($user->file); // on récupère le fichier d'avatar à télécharger
            $user->setAvatar ($fileName);           // et on le met en base
            $manager->persist($user);
            $manager->flush();
            
                    
                $this->addFlash(
                    'Confirmation : ',
                    'Votre compte a été créé. Vous pouvez vous connecter !'
                );

            return $this->redirectToRoute('security_login'); // Après inscription on est redirigé sur le formulaire de login
        }
        
        return $this->render('security/registration.html.twig', [ // Pour s'inscrire on est envoyé sur le formulaire correspondant
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {
                                
    }
}
