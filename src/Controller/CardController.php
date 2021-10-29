<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Image;
use App\Entity\Project;
use App\Form\CardType;
use App\Repository\CardRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

        $card = new Card();
        $image = new Image();
        $date = new \Datetime();

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($laRequete);
        if ($form->isSubmitted() && $form->isValid())
        {
            $imgSend = $form->get('img')->getData();

            try {
                $newNameImage = uniqid() . "." . $imgSend->guessExtension();
                $imgSend->move($this->getParameter('images_cards'), $newNameImage);
                $image->setUrl($newNameImage);
                $manager->persist($image);
                $manager->flush();


                $card->setImage($image);
                $card->setProject($project);
                $card->setCreatedAt($date);

                $manager->persist($card);
                $manager->flush();


                $id = $project->getId();

                return $this->redirect("http://localhost:8000/card/project/". $id);

            } catch (FileException $e) {
                throw $e;

                dd("erreur". $e);
            }


        }else {
            $AllProject = $projectRepository->findAll();

            return $this->render('card/project.html.twig', [
                'projects' => $AllProject,
                'projectshow' => $project,
                'formCard' => $form->createView()
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
