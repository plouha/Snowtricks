<?php

namespace App\Service;

use App\Form\CommentType;
use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager; // donne accès à toutes les méthodess (find, persist, ...)

class CommentPaginationService {
    
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $article;
    
    public function __construct(ObjectManager $manager) {   // on crée un constructeur
        
        $this->manager = $manager; // et partout dans la classe on a accès à Doctrine via le manager
    }
    
    public function setArticle(Article $article): self
    {
        $this->article = $article;
        return $this;
    }
    
    public function getPages() {    // méthode pour trouver le nombre de pages
        
        // Trouve le nombre d'enregistrements de la table
        $repo = $this->manager->getRepository(Comment::class);
        
        $total = $repo->createQueryBuilder("n")->select("COUNT(n.id)")->where("n.article = :article")->setParameter("article", $this->article)->getQuery()->getSingleScalarResult();
        
        // Fait la division et trouve l'arrondi 
        $pages = ceil($total / $this->limit);
        
        // ... et le renvoie
        return $pages;
    }
    
    public function getData() {     // méthode principale qui va retourner les infos pour paginer
        
        // on calcule d'abord l'offset ... le point de départ (le $start qui était dans le controller)
        $offset = $this->currentPage * $this->limit - $this->limit;
        
        // on demande au repositary de trouver les éléments nécessaires
        $repo = $this->manager->getRepository(Comment::class);
        $data = $repo->findBy(["article" => $this->article], ["createdAt" => "desc"], $this->limit, $offset); // = comments par article, et date desc (les 2 [])
        
        // et on renvoit les éléments trouvés
        return $data;
    }
    
    public function setPage($page) {
        
        $this->currentPage = $page;
        return $this;
        
    }
    
    public function getPage() {
        
        return $this->currentPage;
        
    }
    
    public function setLimit($limit) {
        
        $this->limit = $limit;
        return $this;
        
    }
    
    public function getLimit() {
        
        return $this->limit;
        
    }
    
    public function setEntityClass($entityClass) {
        
        $this->entityClass = $entityClass;
        return $this;
        
    }
    
    public function getEntityClass() {
        
        return $this->entityClass;
        
    }
}