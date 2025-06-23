<?php

namespace App\Controller;

use App\Entity\Ingredient; 
use App\Form\IngredientType; 
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $ingredientRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/creation', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès !'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ingredient/edition/{id}', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(IngredientRepository $repository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = $repository->findOneBy(["id" => $id]);

        if (!$ingredient) {
            $this->addFlash(
                'danger',
                "L'ingrédient demandé n'a pas été trouvé."
            );
            return $this->redirectToRoute('app_ingredient');
        }

        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient); 
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succès !'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'ingredient' => $ingredient 
        ]);
    }

    #[Route('/ingredient/suppression/{id}', name: 'app_ingredient_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        if (!$ingredient) { 
            $this->addFlash(
                'danger',
                "L'ingrédient n'a pas été trouvé ou n'a pas pu être chargé."
            );
            return $this->redirectToRoute('app_ingredient');
        }

        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès !'
        );

        return $this->redirectToRoute('app_ingredient');
    }
}