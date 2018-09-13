<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;


class BlogController extends Controller
{
    
    /**
     * @Route("/", name="home")
     */
    public function home(EntityManagerInterface $manager) 
    {
        $articles = $manager->getRepository(Article::class)->findAll();
        return $this->render('blog/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/blog/", name="blog_create")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $article = new Article();           // on crée une instance de la classe Article

                                            // $form est un objet contenant les éléments du form
        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);     // on demande l'analyse de la requete


        if($form->isSubmitted() && $form->isValid()) {  // si formulaire soumis et valide

            $article->setCreatedAt(new \DateTime);      // on crée la date du moment

            $manager->persist($article);                // on fait persister l'article
            $manager->flush();                          // on enregistre en base de données

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);  // on va sur l'article créé
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),       // on passe à TWIG le résultat des éléments
        ]);                                             // dont il a besoin et on fait la vue
    }


    /**
     * @Route("/blog/show/{id}", name="blog_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/blog/update/{id}", name="blog_update")
     */
    public function update(Article $article, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {      // si formulaire soumis et valide
        
            $manager->flush();                              // on enregistre en base de données

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);  // on va sur l'article créé
        }
        return $this->render('blog/update.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/delete/{id}", name="blog_delete", methods={"GET", "POST"})
     */
    
    public function delete(Article $article, Request $request, ObjectManager $manager)
    {
        $formBuilder = $this->createFormBuilder();      //on crée un objet formulaire vide ... on y ajoute un bouton "submit"
        $formBuilder->add("submit", SubmitType::class, array('attr' => array('class' => 'btn btn-danger')));
        $form = $formBuilder->getForm();                //on construit l'objet formulaire
        
        $form->handleRequest($request);                 //on analyse et on le form comme requete


            if($form->isSubmitted())                    //s'il a été soumis on exécute le code dessous
            {
                $manager->remove($article);
                $manager->flush();

                $this->addFlash(
                    'Confirmation : ',
                    'Article supprimé !'
                );

            return $this->redirectToRoute('home');
            }
        
        return $this->render('blog/delete.html.twig', [ // s'il n'a pas été soumis on appelle le fichier twig
            'form' => $form->createView()               // et on crée la vue du fichier de confirmation
        ]);
    }
}
