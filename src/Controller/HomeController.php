<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        PaginatorInterface $paginator
    ): Response {
        // Get filter parameters
        $search = $request->query->get('search') ?: null;
        $categoryId = $request->query->get('category') !== '' && $request->query->get('category') !== null 
            ? (int) $request->query->get('category') 
            : null;
        $authorId = $request->query->get('author') !== '' && $request->query->get('author') !== null 
            ? (int) $request->query->get('author') 
            : null;

        // Get books based on filters
        $query = $bookRepository->searchBooks($search, $categoryId, $authorId);

        // Paginate the results
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12 // items per page
        );

        return $this->render('home/index.html.twig', [
            'books' => $pagination,
            'categories' => $categoryRepository->findAllOrdered(),
            'authors' => $authorRepository->findAllOrdered(),
            'current_search' => $search,
            'current_category' => $categoryId,
            'current_author' => $authorId,
        ]);
    }

    #[Route('/book/{id}', name: 'app_book_show', requirements: ['id' => '\d+'])]
    public function show(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }

        return $this->render('home/book_show.html.twig', [
            'book' => $book,
        ]);
    }
}
