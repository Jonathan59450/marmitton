<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface; // Importe l'interface du Paginator
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Importe la classe Request pour récupérer le numéro de page
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ingredient; // Pour l'entité
use App\Form\IngredientType; // Pour le formulaire IngredientType
use Doctrine\ORM\EntityManagerInterface; // Pour la persistance en base de données

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    #[Route('/ingredient/nouveau', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $manager
): Response
{
    $ingredient = new Ingredient(); // Crée une nouvelle instance d'Ingredient
    $ingredient->setCreatedAt(new \DateTimeImmutable()); // Définit la date de création

    // Crée le formulaire IngredientType
    $form = $this->createForm(IngredientType::class, $ingredient);

    // Gère la soumission du formulaire
    $form->handleRequest($request);

    // Vérifie si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        $ingredient = $form->getData(); // Récupère les données validées du formulaire

        $manager->persist($ingredient); // Prépare l'enregistrement de l'ingrédient en base de données
        $manager->flush(); // Exécute l'enregistrement

        // Ajoute un message flash de succès
        $this->addFlash(
            'success',
            'Votre ingrédient a été créé avec succès !'
        );

        return $this->redirectToRoute('app_ingredient'); // Redirige vers la liste des ingrédients
    }

    // Rend le template 'ingredient/new.html.twig' en lui passant le formulaire
    return $this->render('ingredient/new.html.twig', [
        'form' => $form->createView() // Passe la vue du formulaire au template
    ]);
}
     public function index(
        IngredientRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        // Récupère la requête DQL de tous les ingrédients
        $queryBuilder = $repository->createQueryBuilder('i')->orderBy('i.name', 'ASC');

        // Paginate the results of the query
        $ingredients = $paginator->paginate(
            $queryBuilder, // Requête à paginer (pas un tableau, mais un QueryBuilder)
            $request->query->getInt('page', 1), // Numéro de page actuel (récupéré depuis l'URL, par défaut 1)
            10 // Nombre d'éléments par page
        );

        // Rend le template 'ingredient/index.html.twig' en lui passant les ingrédients paginés
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients, // La variable 'ingredients' est maintenant un objet paginé
        ]);
    }
}