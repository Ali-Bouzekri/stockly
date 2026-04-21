<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
//use App\Entity\Unit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProduitType;


#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(
        ProduitRepository $produitRepository,
        CategorieRepository $catRepo,
        SousCategorieRepository $subCatRepo,
        UnitRepository $unitRepo
    ): Response {
        return $this->render('produit/index.html.twig', [
            'produits'        => $produitRepository->findAll(),
            'categories'      => $catRepo->findAll(),
            'sous_categories' => $subCatRepo->findAll(),
            'units'           => $unitRepo->findAll(),
        ]);
    }

    /**
     * Handles both:
     *  - GET  → standalone "Add Product" page (new.html.twig)
     *  - POST → save from the inline modal (JSON response) OR the standalone page
     */
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        CategorieRepository $catRepo,
        SousCategorieRepository $subCatRepo,
        UnitRepository $unitRepo
    ): Response {

        if ($request->isMethod('POST')) {

            $produit = new Produit();
            $produit->setDesignation(trim($request->request->get('designation', '')));
            $produit->setDescription($request->request->get('description') ?: null);
            $produit->setQteStock((int) $request->request->get('qteStock', 0));
            $produit->setSeuilAlert((int) $request->request->get('seuilAlert', 0));

            $subCatId = $request->request->get('sousCategorie');
            if ($subCatId) {
                $produit->setSousCategorie($subCatRepo->find($subCatId));
            }

            $uniteId = $request->request->get('unite');
            if ($uniteId) {
                $produit->setUnite($unitRepo->find($uniteId));
            }

            // Basic validation
            if (!$produit->getDesignation()) {
                if ($request->isXmlHttpRequest()) {
                    return $this->json(['success' => false, 'error' => 'Product name is required.'], 422);
                }
            } else {
                $em->persist($produit);
                $em->flush();

                // AJAX call from the modal → return JSON
                if ($request->isXmlHttpRequest()) {
                    return $this->json(['success' => true]);
                }

                // Regular form submit (standalone page) → redirect
                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        // GET → render the standalone new-product page
        return $this->render('produit/new.html.twig', [
            'categories'      => $catRepo->findAll(),
            'sous_categories' => $subCatRepo->findAll(),
            'units'           => $unitRepo->findAll(),
        ]);
    }

    /**
     * JSON endpoint: returns subcategories for a given category ID.
     * Called by the modal's JS when the user picks a category.
     */
    #[Route('/api/subcategories/{id}', name: 'app_produit_subcategories_json', methods: ['GET'])]
    public function subcategoriesJson(int $id, SousCategorieRepository $subCatRepo): JsonResponse
    {
        $subs = $subCatRepo->findBy(['categorie' => $id]);

        $data = array_map(fn($s) => [
            'id'   => $s->getIdSousCat(),
            'name' => $s->getNomSousCat(),
        ], $subs);

        return $this->json($data);
    }

    #[Route('/edit/{idProduit}', name: 'app_produit_edit', methods: ['POST'])]
public function edit(
    int $idProduit,
    Request $request,
    ProduitRepository $produitRepository,
    EntityManagerInterface $em,
    SousCategorieRepository $subCatRepo,
    UnitRepository $unitRepo
): JsonResponse {
    $produit = $produitRepository->find($idProduit);

    if (!$produit) {
        return $this->json(['success' => false, 'error' => 'Product not found'], 404);
    }

    // Update fields from the modal
    $produit->setDesignation(trim($request->request->get('designation', '')));
    $produit->setDescription($request->request->get('description') ?: null);
    $produit->setQteStock((int) $request->request->get('qteStock', 0));
    $produit->setSeuilAlert((int) $request->request->get('seuilAlert', 0));

    // Update SubCategory Relationship
    $subCatId = $request->request->get('sousCategorie');
    if ($subCatId) {
        $produit->setSousCategorie($subCatRepo->find($subCatId));
    }

    // Update Unit Relationship
    $uniteId = $request->request->get('unite');
    if ($uniteId) {
        $produit->setUnite($unitRepo->find($uniteId));
    }

    $em->flush();

    return $this->json(['success' => true]);
}

#[Route('/{idProduit}/delete', name: 'app_produit_delete', methods: ['POST'])]
public function delete(
    Request $request,
    #[MapEntity(mapping: ['idProduit' => 'idProduit'])] Produit $produit,
    EntityManagerInterface $em
): Response {
    if ($this->isCsrfTokenValid('delete' . $produit->getIdProduit(), $request->request->get('_token'))) {
        $em->remove($produit);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true]);
        }
    }

    return $this->redirectToRoute('app_produit_index');
}

#[Route('/{idProduit}', name: 'app_produit_show', methods: ['GET'])]
public function show(
    #[MapEntity(mapping: ['idProduit' => 'idProduit'])] Produit $produit
): Response {
    return $this->render('produit/show.html.twig', ['produit' => $produit]);

}






}