<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BookController extends AbstractController
{
    protected TranslatorInterface $translator;

    protected BookRepository $bookRepository;

    public function __construct(TranslatorInterface $translator, BookRepository $bookRepository)
    {
        $this->translator = $translator;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("/book/create", name="book_create")
     */
    public function create()
    {
        return $this->json([]);
    }

    /**
     * @Route("/book/search", name="book_search")
     */
    public function search(Request $request)
    {
        return $this->json([]);
    }

    /**
     * @Route("/{_locale<en|ru>}/book/{bookId}", name="book_item")
     */
    public function item(int $bookId)
    {
        $book = $this->bookRepository->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException();
        }

        return $this->json([
            'Id' => $book->getId(),
            'Name' => $this->translator->trans($book->getName(), [], 'books'),
            'Author' => $book->getAuthors()->map(
                fn (Author $author) => [
                    'Id' => $author->getId(),
                    'Name' => $author->getName()
                ]
            )->toArray(),
        ]);
    }
}
