<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




/**
 * @Route("/post")
 * 
 */
class PostController extends AbstractController
{

    /**
     * @Route("/add{id}", name="add_post")
     */

    public function addPost(Request $request, EntityManagerInterface $manager, Topic $topic) {

       $post = new Post();
        
        $form = $this->createform(Post::class, $post);
        $form->handleRequest($request); //

        if($form->isSubmitted() && $form->isValid()){

            $post->setUser($this->getUser());//this get user recupere les infos de la session
            $post->setTopic($topic);
            $manager->persist($post);//phase de préparation
            $manager->flush();//envoie
        
            return $this->redirectToRoute('topic_show', ['id'=>$post->getTopic()->getId()]);
        }
       

        return $this->render("post/show.html.twig", [
            'formPost' => $form->createView()
        ]);

    }

    /**
     * @Route("/{id}deletePost", name="post_delete")
     */
    public function deletePost(Post $post, EntityManagerInterface $manager) {
        $manager->remove($post);
        $manager->flush();

        return $this->redirectToRoute('topic_show', ['id'=>$post->getTopic()->getId()]);
    }

  
     /**
     * @Route("/", name="posts_index")
     */
    public function index()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([], ['creationDate' => 'DESC']);
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

      /**
     * @Route("/{id}/show", name="post_show", methods="GET")
     */

    public function show(Post $post): Response {

        return $this->render('post/show.html.twig', ['post' => $post]);
    }

}
