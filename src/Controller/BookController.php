<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'book_')]
class BookController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $books = $em->getRepository(Book::class)->findAll();
        return $this->render('book/index.html.twig', ['books' => $books]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setPublished(true);
            $author = $book->getAuthor();

            if ($author) {
                $author->setNbBooks($author->getNbBooks() + 1);
                $em->persist($author);
            }

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $author = $book->getAuthor();

        if ($author) {
            $author->setNbBooks(max(0, $author->getNbBooks() - 1));
            $em->persist($author);
        }

        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('book_index');
    }
}
