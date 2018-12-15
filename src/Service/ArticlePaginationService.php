<?php

namespace App\Service;

use App\Form\CommentType;
use App\Entity\Article;
use Symfony\Config\Routes;
use Doctrine\Common\Persistence\ObjectManager; // donne accès à toutes les méthodes (find, persist, ...)


class ArticlePaginationService {
    
    private $entityClass;
    private $limit = 6;
    private $currentPage = 1;
    private $manager;
    
    public function __construct(ObjectManager $manager) {   // on crée un constructeur
        
        $this->manager = $manager; // et partout dans la classe on a accès à Doctrine via le manager
    }
    
    public function getPages() {    // méthode pour trouver le nombre de pages
        
        // Trouve le nombre d'enregistrements de la table
        $repo = $this->manager->getRepository(Article::class);
        
        $total = $repo->createQueryBuilder("n")->select("COUNT(n.id)")->getQuery()->getSingleScalarResult();
        
        // Fait la division et trouve l'arrondi 
        $pages = ceil($total / $this->limit);
        
        // ... et le renvoie
        return $pages;
    }
    
    public function getData() {     // méthode principale qui va retourner les infos pour paginer
        
        // on calcule d'abord l'offset ... le point de départ (le $start qui était dans le controller)
        $offset = $this->currentPage * $this->limit - $this->limit;
        
        // on demande au repositary de trouver les éléments nécessaires
        $repo = $this->manager->getRepository(Article::class);
        $data = $repo->findBy([], ["createdAt" => "asc"], $this->limit, $offset); // = pas de recherche particulière, ni d'ordre (les 2 [])
        
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