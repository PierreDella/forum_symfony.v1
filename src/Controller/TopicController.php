<?php

namespace App\Controller;

use index;
use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Topic;
use App\Form\TopicType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Doctrine\DBAL\Types\BooleanType;


/**
 * @Route("/topic")
 */

class TopicController extends AbstractController
{

    /**
     * @Route("/add/{id}", name="add_topic")
     * 
     */


    public function addTopic( Request $request, EntityManagerInterface $manager, Categorie $categorie) {


        $topic = new Topic();

        $form = $this->createForm(TopicType::class, $topic);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
       
            $topic->setUser($this->getUser());
            $topic->setCategorie($categorie);
            $topic->setLocked(false);
            $manager->persist($topic);
            $manager->flush();


        return $this->redirectToRoute('categorie_show', ['id'=> $topic->getCategorie()->getId()]);
        }   

        return $this->render("topic/add.html.twig", [
            'formTopic' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}deleteTopic", name="topic_delete")
     */
    public function deleteTopic(Topic $topic, EntityManagerInterface $manager) {
        $manager->remove($topic);
        $manager->flush();

        return $this->redirectToRoute('categorie_show', ['id'=> $topic->getCategorie()->getId()]);
    }



    /**
     * @Route("/", name="topics_index")
     */
    public function index()
    {
        $topics = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->findBy([], ['creationDate' => 'DESC']);
        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
        ]);
    }
    /**
     * @Route("/{id}", name="topic_show", methods="GET")
     */
    public function show(Topic $topic): Response {
        return $this->render('topic/show.html.twig', ['topic' => $topic]);
    }
}
