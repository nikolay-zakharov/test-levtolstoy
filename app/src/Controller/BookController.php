<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class BookController extends AbstractController
{
    protected TranslatorInterface $translator;

    protected BookRepository $bookRepository;

    protected AuthorRepository $authorRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        TranslatorInterface $translator,
        BookRepository $bookRepository,
        AuthorRepository $authorRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->translator = $translator;
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/book/create", name="book_create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $requestData = $request->toArray();

        if (empty($requestData['name']) || empty($requestData['authors'])) {
            return $this->json(['error' => true, 'message' => 'Name and Authors are required'], 422);
        }

        try {
            $book = new Book();
            $book->setName($requestData['name']);
            $this->entityManager->persist($book);
            $this->entityManager->flush();

            $authors = $this->authorRepository->findByIdIn($requestData['authors']);
            $book->setAuthors($authors);
            $this->entityManager->flush();

            return $this->json(['success' => true]);
        } catch (Throwable $t) {
            return $this->json(['error' => true, 'message' => $t->getMessage(), 'trace' => $t->getTraceAsString()], 422);
        }
    }

    /**
     * @Route("/book/search", name="book_search", methods={"GET"})
     */
    public function search(Request $request)
    {
        $pageSize = 100;
        $page = max(1, (int) $request->get('page'));
        $query = $request->query->get('query', '');

        $books = $this->bookRepository->findByNamePartPaginated($query, $page, $pageSize);

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
     * @Route("/{_locale<en|ru>}/book/{bookId}", name="book_item", methods={"GET"})
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
