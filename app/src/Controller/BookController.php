<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $pageSize = 100;
        $page = max(1, (int) $request->get('page'));

        $books = $this->bookRepository->findByNamePart($request->query->get('query', ''), $page, $pageSize);
        $booksArray = array_map(
            fn (Book $book) => $this->getBookResponseItem($book),
            $books->getIterator()->getArrayCopy()
        );

        return $this->json($booksArray, 200, [
            'Total-Items-Count' => $books->count(),
            'Items-Count' => count($booksArray),
            'Page' => $page,
            'Total-Pages' => (int) ceil($books->count()/$pageSize),
        ]);
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

        return $this->json($this->getBookResponseItem($book));
    }

    protected function getBookResponseItem(Book $book)
    {
        return [
            'Id' => $book->getId(),
            'Name' => $this->translator->trans($book->getName(), [], 'books'),
            'Author' => $book->getAuthors()->map(
                fn (Author $author) => [
                    'Id' => $author->getId(),
                    'Name' => $author->getName()
                ]
            )->toArray(),
        ];
    }
}
