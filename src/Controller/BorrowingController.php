<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\BorrowingHistoryRepository;
use App\Service\BorrowingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/borrow')]
#[IsGranted('ROLE_USER')]
class BorrowingController extends AbstractController
{
    #[Route('/history', name: 'app_borrowing_history')]
    public function history(BorrowingHistoryRepository $borrowingHistoryRepository): Response
    {
        $user = $this->getUser();
        $borrowings = $borrowingHistoryRepository->findByUser($user);
        $activeBorrowings = $borrowingHistoryRepository->findActiveBorrowings($user);

        return $this->render('borrowing/history.html.twig', [
            'borrowings' => $borrowings,
            'active_borrowings' => $activeBorrowings,
        ]);
    }

    #[Route('/book/{id}', name: 'app_borrow_book', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function borrowBook(
        int $id,
        BookRepository $bookRepository,
        BorrowingService $borrowingService
    ): Response {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }

        $user = $this->getUser();

        // Check if user already has an active borrowing for this book
        if ($borrowingService->hasActiveBorrowing($user, $book)) {
            $this->addFlash('warning', 'You have already borrowed this book.');
            return $this->redirectToRoute('app_book_show', ['id' => $id]);
        }

        // Try to borrow the book
        $borrowing = $borrowingService->borrowBook($user, $book);

        if ($borrowing) {
            $this->addFlash('success', sprintf(
                'You have successfully borrowed "%s". Please return it by %s.',
                $book->getTitle(),
                $borrowing->getDueDate()->format('F j, Y')
            ));
        } else {
            $this->addFlash('error', 'Sorry, this book is currently not available.');
        }

        return $this->redirectToRoute('app_book_show', ['id' => $id]);
    }

    #[Route('/return/{id}', name: 'app_return_book', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function returnBook(
        int $id,
        BorrowingHistoryRepository $borrowingHistoryRepository,
        BorrowingService $borrowingService
    ): Response {
        $borrowing = $borrowingHistoryRepository->find($id);

        if (!$borrowing) {
            throw $this->createNotFoundException('The borrowing record does not exist');
        }

        // Check if the borrowing belongs to the current user
        if ($borrowing->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only return your own borrowed books.');
        }

        if ($borrowingService->returnBook($borrowing)) {
            $this->addFlash('success', sprintf(
                'You have successfully returned "%s". Thank you!',
                $borrowing->getBook()->getTitle()
            ));
        } else {
            $this->addFlash('warning', 'This book has already been returned.');
        }

        return $this->redirectToRoute('app_borrowing_history');
    }
}
