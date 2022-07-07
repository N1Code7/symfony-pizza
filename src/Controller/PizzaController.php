<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/pizza")]
class PizzaController extends AbstractController
{
    #[Route('/list', name: 'app_pizza_list')]
    public function displayAllPizza(PizzaRepository $repository): Response
    {
        $pizzas = $repository->findAll();

        return $this->render("/pizza/list.html.twig", [
            "pizzas" => $pizzas
        ]);
    }

    #[Route("/new", name: "app_pizza_new")]
    public function createPizza(Request $request, PizzaRepository $repository): Response
    {
        if (!$request->isMethod("POST")) {
            return $this->render("pizza/new.html.twig");
        }

        $name = $request->request->get("name");
        $desc = $request->request->get("desc");
        $price = (float)$request->request->get("price");
        $imageUrl = $request->request->get("imageUrl");

        $pizza = new Pizza();
        $pizza->setName($name);
        $pizza->setDescription($desc);
        $pizza->setPrice($price);
        $pizza->setImageUrl($imageUrl);

        $repository->add($pizza, true);

        return $this->redirectToRoute("app_pizza_list");

        // return new Response("La pizza possédant l'ID n° {$pizza->getId()} a bien été enregistrée.");
    }
}
