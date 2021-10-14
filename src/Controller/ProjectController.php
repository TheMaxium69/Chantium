<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        $AllProject = $projectRepository->findAll();

        $user = $this->getUser();

        return $this->render('project/index.html.twig', [
            'projects' => $AllProject,
            'user' => $user
        ]);
    }

    /**
     * @Route("/home/create/", name="projectCreate")
     */
    public function new(Project $project = null, Request $laRequete, EntityManagerInterface $manager) : Response
    {
         $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($laRequete);
        if ($form->isSubmitted() && $form->isValid())
        {
            $imgSend = $form->get('img')->getData();

            try {
                $newNameImage = uniqid() . "." . $imgSend->guessExtension();
                $imgSend->move($this->getParameter('images_projects'), $newNameImage);
                //$project->setImg($newNameImage);

            } catch (FileException $e) {
                throw $e;
            }

            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('home');
        }else {
            return $this->render('project/form.html.twig', [
                'formProject' => $form->createView()
            ]);
        }

    }


    /**
     * @Route("/project/show/{id}", name="projectShow")
     */
    public function show(Project $project, ProjectRepository $projectRepository): Response
    {
        $AllProject = $projectRepository->findAll();

        return $this->render('project/show.html.twig', [
            'projects' => $AllProject,
            'projectshow' => $project
        ]);
    }



}
