<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    private $projectDir;

    /**
     * @Route("/", name="app_index")
     */
    public function indexAction(): Response
    {
        $version = Kernel::VERSION;
        $baseDir = realpath($this->projectDir).\DIRECTORY_SEPARATOR;
        $docVersion = substr(Kernel::VERSION, 0, 3);

        ob_start();
        include '../templates/Welcome.html.php';

        return new Response(ob_get_clean(), Response::HTTP_OK);

    }
}

