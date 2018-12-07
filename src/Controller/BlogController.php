<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Routes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Video;
use App\Entity\Photo;
use App\Entity\Upload;
use App\Entity\UploadedFile;
use App\Service\FileUploader;
use App\Service\ArticlePaginationService;
use App\Service\CommentPaginationService;
use App\Form\ArticleType;
use App\Form\TexteType;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\Form\PhotoType;
use App\Form\VideoType;
use App\Controller\CommentRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping as ORM;


class BlogController extends Controller
{
    
    /**
     * @Route("/{page<\d+>?1}", name="home")
     */
    public function home(EntityManagerInterface $manager, $page, ArticlePaginationService $pagination) 
    {   
        $pagination->setPage($page);                   // indique la page concernée
        
        return $this->render('blog/home.html.twig', [
            'pagination' => $pagination                 // on passe les données de pagination

            //'pages_range' => range(max(1, $page-2), min($pages, $page+2))
        ]);
    }

    /**
     * @Route("/blog/", name="blog_create")
     */
    public function create(Request $request, ObjectManager $manager, FileUploader $fileUploader)
    {
        $article = new Article();           // on crée une instance de la classe Article

                                            // $form est un objet contenant les éléments du form
        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);     // on demande l'analyse de la requete


        if($form->isSubmitted() && $form->isValid()) {  // si formulaire soumis et valide

            $article->setCreatedAt(new \DateTime);      // on crée la date du moment
            $fileName = $fileUploader->upload($article->file); // on récupère le fichier à télécharger
            $article->setImage ( $fileName );           // et on le met en base

            
                                                        // Pour les collections de photos et vidéos liées à chaque article
            foreach($article->getPhotos() as $photo) {
                if($photo->file !== null) {
                    $fileName = $fileUploader->upload($photo->file); // on télécharge le fichier à récupèrer
                    $photo->setName ( $fileName ); 
                }
            }
            
            foreach($article->getVideos() as $video) {      // On change l'url pour qu'elle finisse par /embed/nom du fichier youtube
                if($video->name !== null) {                 // en utilisant le fonction PHP str_replace
                    $videoName 	= str_replace('youtu.be/', 'www.youtube.com/embed/', $video); 
		            $videoName 	= str_replace('www.youtube.com/watch?v=', 'www.youtube.com/embed/', $video);
                    $video->setName ($videoName);
                }
            }   
            
            $manager->persist($article);                // on fait persister l'article et toutes ses composantes
            $manager->flush();                          // on enregistre en base de données

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);  // on va sur l'article créé
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),       // on passe à TWIG le résultat des éléments
        ]);                                             // dont il a besoin et on fait la vue
    }


    /**
     * @Route("/blog/show/{id}/{page<\d+>?1}", name="blog_show")
     */
    public function show(Article $article, Request $request, ObjectManager $manager, $page, CommentPaginationService $pagination)
    {
        $comment = new Comment();           // on crée une instance de la classe Comment
        
                                            // $form est un objet contenant les éléments du form
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);     // on demande l'analyse de la requete

                    
        if($form->isSubmitted() && $form->isValid()) {  // si formulaire soumis et valide

            $comment->setCreatedAt(new \DateTime());    //on récupère la date actuelle
            $comment->setArticle($article);             //on récupère l'id de l'article lié
            $comment->setSignaled("0");                 // Par défaut on indique ... "non signalé"
            $comment->setUser($this->getUser());        //on récupère l'id de l'auteur           

            $manager->persist($comment);                // on fait persister $article

            $manager->flush();                          // on enregistre en base de données

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);  // on retourne sur l'article mis à jour
        }
        
    
        $pagination ->setArticle($article)
                    ->setLimit(5)                           // indique la limite ... ici différente (5 au lieu de 6)
                    ->setPage($page);                       // indique la page concernée (1 par défaut)
                    
        return $this->render('blog/show.html.twig', [
            'article' => $article,                      // on passe les données de l'article
            'pagination' => $pagination,                // on envoie les informations pour la pagination des commentaires
            'formComment' => $form->createView()        // on passe à TWIG le résultat des éléments pour le formulaire
        ]);                                             // dont il a besoin et on fait la vue
        
    }
    
    /**
     * @Route("/blog/signal/{id}", name="blog_signal")
     */
    public function signal(Comment $comment, Request $request, ObjectManager $manager)
    {
        $formBuilder = $this->createFormBuilder();      //on crée un objet formulaire vide ... on y ajoute un bouton "submit"
        $formBuilder->add("Signaler", SubmitType::class, array('attr' => array('class' => 'btn btn-danger')));
        $form = $formBuilder->getForm();                //on construit l'objet formulaire
        
        $form->handleRequest($request);                 //on analyse et on le form comme requete


            if($form->isSubmitted())                    //s'il a été soumis on exécute le code dessous
            {
                $comment->setSignaled("1");             //on change la valeur du booléen
                $manager->persist($comment);
                $manager->flush();

                $this->addFlash(
                    'Confirmation : ',
                    'Commentaire signalé !'
                );

            return $this->redirectToRoute('blog_show', ['id' => $comment->getArticle()->getId()]);  // on va sur l'article & commentaire signalé
            }        
        
        return $this->render('blog/signal.html.twig', [ // s'il n'a pas été soumis on appelle le fichier twig
            'form' => $form->createView()               // et on crée la vue du fichier de confirmation
        ]);                                             // dont il a besoin et on fait la vue
        
    }    

    /**
     * @Route("/blog/update/{id}", name="blog_update")
     */
    public function update(Article $article, Request $request, ObjectManager $manager, FileUploader $fileUploader)
    {

        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {              // si formulaire soumis et valide
            if($article->file !== null) {
                $fileName = $fileUploader->upload($article->file);  // on récupère le fichier à télécharger
                $article->setImage ( $fileName ); 
            }
            
            foreach($article->getPhotos() as $photo) {
                if($photo->file !== null) {
                    $fileName = $fileUploader->upload($photo->file); // on récupère le fichier à télécharger
                    $photo->setName ( $fileName );                   // et on le met en base
                }
            }
            
            foreach($article->getVideos() as $video) {
                if($video->name !== null) {
                    $videoName 	= str_replace('youtu.be/', 'www.youtube.com/embed/', $video);
		            $videoName 	= str_replace('www.youtube.com/watch?v=', 'www.youtube.com/embed/', $video);
                    $video->setName ($videoName);
                }

            }
            
            $manager->persist($article);
            $manager->flush();                                  // on enregistre en base de données

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
        $formBuilder->add("Supprimer", SubmitType::class, array('attr' => array('class' => 'btn btn-danger')));
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



