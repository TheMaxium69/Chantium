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
    public function index(ProjectRepository $projectRepository, Request $laRequete, EntityManagerInterface $manager): Response
    {
        $card = new Card();
        $image = new Image();
        $date = new \Datetime();

        $AllProject = $projectRepository->findAll();

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($laRequete);
        if ($form->isSubmitted() && $form->isValid())
        {
            $id = $_POST['id'];
            $projectArray = $projectRepository->findBy(array('id' => $id));
            $project = $projectArray['0'];

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

                return $this->redirect("/card/project/". $id . "?isvalide=true&name=" . $card->getTitle());

            } catch (FileException $e) {
                throw $e;

                dd("erreur". $e);
            }


        } else {

            return $this->render('card/index.html.twig', [
                'projects' => $AllProject,
                'formCard' => $form->createView(),
                'isValide' => null
            ]);
        }
    }

    /**
     * @Route("/card/project/{id}", name="cardProject")
     */
    public function cardProject(Project $project, ProjectRepository $projectRepository, Request $laRequete, EntityManagerInterface $manager): Response
    {

        $card = new Card();
        $image = new Image();
        $date = new \Datetime();

        $AllProject = $projectRepository->findAll();

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($laRequete);
        if ($form->isSubmitted() && $form->isValid()) {
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

                return $this->redirect("/card/project/" . $id . "?isvalide=true&name=" . $card->getTitle());

            } catch (FileException $e) {
                throw $e;

                dd("erreur" . $e);
            }


        } else if (!empty($_GET['isvalide'])) {

            $isValide = $_GET['isvalide'];
            $title = $_GET['name'];

            return $this->render('card/project.html.twig', [
                'projects' => $AllProject,
                'projectshow' => $project,
                'formCard' => $form->createView(),
                'isValide' => $isValide,
                'title' => $title
            ]);
        } else {

            return $this->render('card/project.html.twig', [
                'projects' => $AllProject,
                'projectshow' => $project,
                'formCard' => $form->createView(),
                'isValide' => null
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

    /**
     * @Route("/card/{id}", name="cardEdit")
     */
    public function cardedit(Card $card, ProjectRepository $projectRepository, Request $laRequete, EntityManagerInterface $manager): Response
    {
        $image = new Image();
        $date = new \Datetime();

        $AllProject = $projectRepository->findAll();
        $project = $card->getProject();


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

                return $this->redirect("/card/project/". $id . "?isvalide=edit&name=" . $card->getTitle());

            } catch (FileException $e) {
                throw $e;

                dd("erreur". $e);
            }

        } else {

            return $this->render('card/project.html.twig', [
                'projects' => $AllProject,
                'projectshow' => $project,
                'formCard' => $form->createView(),
                'isValide' => null
            ]);
        }
    }
}
