<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class AuthorController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/author/create", name="author_list", methods={"POST"})
     */
    public function create(Request $request)
    {
        $requestData = $request->toArray();

        if (empty($requestData['name'])) {
            return $this->json(['error' => true, 'message' => 'Name is required'], 422);
        }

        try {
            $author = new Author();
            $author->setName($requestData['name']);
            $this->entityManager->persist($author);
            $this->entityManager->flush();

            return $this->json(['success' => true]);
        } catch (Throwable $t) {
            return $this->json(['error' => true, 'message' => $t->getMessage(), 'trace' => $t->getTraceAsString()], 422);
        }
    }
}
