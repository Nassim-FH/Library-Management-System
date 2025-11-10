<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/author')]
#[IsGranted('ROLE_ADMIN')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'app_admin_author_index', methods: ['GET'])]
    public function index(
        Request $request,
        AuthorRepository $authorRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $authorRepository->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('admin/author/index.html.twig', [
            'authors' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Author created successfully!');

            return $this->redirectToRoute('app_admin_author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/author/new.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_author_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Author $author): Response
    {
        return $this->render('admin/author/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_author_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Author updated successfully!');

            return $this->redirectToRoute('app_admin_author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/author/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_author_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $entityManager->remove($author);
            $entityManager->flush();

            $this->addFlash('success', 'Author deleted successfully!');
        }

        return $this->redirectToRoute('app_admin_author_index', [], Response::HTTP_SEE_OTHER);
    }
}
