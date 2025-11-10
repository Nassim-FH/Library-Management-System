<?php

namespace App\Controller\Admin;

use App\Repository\BookRepository;
use App\Repository\BorrowingHistoryRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository,
        BorrowingHistoryRepository $borrowingHistoryRepository
    ): Response {
        // Get statistics
        $totalBooks = count($bookRepository->findAll());
        $totalCategories = count($categoryRepository->findAll());
        $totalUsers = count($userRepository->findAll());
        $activeBorrowings = $borrowingHistoryRepository->findAllActiveBorrowings();

        return $this->render('admin/dashboard.html.twig', [
            'total_books' => $totalBooks,
            'total_categories' => $totalCategories,
            'total_users' => $totalUsers,
            'active_borrowings' => $activeBorrowings,
        ]);
    }
}
