<?php
// src/Controller/ReactController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReactController extends AbstractController
{
    /**
     * @Route("/react", name="react_app")
     */
    public function reactApp(): Response
    {
        return $this->render('react_app.html.twig');
    }
}
