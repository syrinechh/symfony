<?php
namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_index')]
    public function index(BookRepository $repo): Response
    {
        $books = $repo->createQueryBuilder('b')
            ->where('b.published = :val')
            ->setParameter('val', true)
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();

        $entityManager = $repo->getEntityManager();
        $publishedCount = $entityManager->createQuery(
            'SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.published = true'
        )->getSingleScalarResult();
        $unpublishedCount = $entityManager->createQuery(
            'SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.published = false'
        )->getSingleScalarResult();

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount,
        ]);
    }

    #[Route('/add', name: 'book_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setPublished(true);

            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'book_edit')]
    public function edit(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/delete/{id}', name: 'book_delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('book_index');
    }

    #[Route('/show/{id}', name: 'book_show')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', ['book' => $book]);
    }
}
