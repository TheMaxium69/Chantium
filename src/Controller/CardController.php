<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/card", name="card")
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        $AllProject = $projectRepository->findAll();

        return $this->render('card/index.html.twig', [
            'projects' => $AllProject,
        ]);
    }

    /**
     * @Route("/card/project/{id}", name="cardProject")
     */
    public function cardProject(Project $project, ProjectRepository $projectRepository, Request $laRequete, EntityManagerInterface $manager): Response
    {

        if (!empty($_GET['test']))
        {
             dd($_GET['test']);

        }else {

            $nb = 1;

            $AllProject = $projectRepository->findAll();

            return $this->render('card/project.html.twig', [
                'projects' => $AllProject,
                'projectshow' => $project,
                "nb" => $nb
            ]);
        }









    }

}
