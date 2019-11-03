<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This name is made so that the controller routes are the last loaded
 * and the wildcard route is the last loaded one. I could use YAML routing to
 * handle order but I prefer annotations.
 */
class ZeDefaultController extends AbstractController
{
    /**
     * @Route(
     *     "/{reactRouting}",
     *     name="homepage",
     *     defaults={"reactRouting": null},
     *     requirements={
     *          "reactRouting"="^((?!(favicon.ico|translations|api/doc|api/doc.json)).)*$"
     *     }
     *     )
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
