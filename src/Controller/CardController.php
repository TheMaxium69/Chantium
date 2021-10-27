<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Project;
use App\Repository\CardRepository;
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

        if (!empty($_POST['title']) && !empty($_POST['content']))
        {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $date = new \Datetime();
            $image = $project->getImage();

            $card = new Card();

            $card->setTitle($title)->setContent($content)->setCreatedAt($date)->setProject($project)->setImage($image);

            $manager->persist($card);
            $manager->flush();

            $id = $project->getId();

            return $this->redirect("http://localhost:8000/card/project/". $id);

        }else {
            $AllProject = $projectRepository->findAll();

            return $this->render('card/project.html.twig', [
                'projects' => $AllProject,
                'projectshow' => $project
            ]);
        }
    }

    /**
     * @Route("/cards/{id}", name="cardAll")
     */
    public function cards(Project $project, CardRepository $cardRepository, ProjectRepository $projectRepository): Response
    {

        $AllCard = $cardRepository->findAll();

        $cards = [];

        foreach($AllCard as $card){
            $projectcard = $card->getProject();
            if ($projectcard == $project){
                array_push($cards, $card);
            }

        }


        $AllProject = $projectRepository->findAll();

        return $this->render('card/show.html.twig', [
            'projects' => $AllProject,
            'projectshow' => $project,
            'cards' => $cards,
        ]);
    }
}
