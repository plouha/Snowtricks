<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use App\Form\CommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\Mapping as ORM;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment", methods={"GET", "POST"})
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->findby(
            ['signaled' => 'true']
            );

        return $this->render('admin_comment/index.html.twig', [
            'comments' => $comments,

        ]);
    }
    
    /**
     * @Route("/admin/comment/update/{id}", name="comment_update", methods={"GET", "POST"})
     */
    public function update(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {          // si formulaire soumis et valide

            $comment->setSignaled("false");        
            $manager->flush();                                  // on enregistre en base de données

            $this->addFlash(                                    // on envoie un message de confirmation
                'Confirmation : ',
                'Commentaire modifié !'
            );
                
            return $this->redirectToRoute('admin_comment');     // on retourne sur l'écran de modération
        }
        return $this->render('admin_comment/update.html.twig', [
            'formComment' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/admin/comment/delete/{id}", name="comment_delete", methods={"GET", "POST"})
     */
    public function delete(Comment $comment, Request $request, ObjectManager $manager)
    {
        $formBuilder = $this->createFormBuilder();      //on crée un objet formulaire vide ... on y ajoute un bouton "submit"
        $formBuilder->add("Supprimer", SubmitType::class, array('attr' => array('class' => 'btn btn-danger')));
        $form = $formBuilder->getForm();                //on construit l'objet formulaire
        
        $form->handleRequest($request);                 //on analyse et on le form comme requete


            if($form->isSubmitted())                    //s'il a été soumis on exécute le code dessous
            {
                $manager->remove($comment);
                $manager->flush();

                $this->addFlash(                        // on envoie un message de confirmation
                    'Confirmation : ',
                    'Commentaire supprimé !'
                );

            return $this->redirectToRoute('admin_comment');
            }
        
        return $this->render('admin_comment/delete.html.twig', [ // s'il n'a pas été soumis on appelle le fichier twig
            'form' => $form->createView()               // et on crée la vue du fichier de confirmation
        ]);
    }
}
