<?php
namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // Exemple DQL : récupérer les livres par catégorie
    public function findByCategoryDQL(string $category)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b FROM App\Entity\Book b WHERE b.category = :cat')
            ->setParameter('cat', $category)
            ->getResult();
    }

    // Exemple QueryBuilder : livres d’un auteur donné
    public function findByAuthorQB($author)
    {
        return $this->createQueryBuilder('b')
            ->where('b.author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult();
    }
}
