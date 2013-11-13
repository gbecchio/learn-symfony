<?php
// src/Sdz/BlogBundle/Controller/BlogController.php
namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    public function indexAction()
    {
        $request = $this->getRequest();
        $tag = $request->query->get('id');
        return new Response('Hello World '.$tag);
        //return $this->render('SdzBlogBundle:Blog:index.html.twig');
    }
    public function alertAction()
    {
        return new Response($this->render('SdzBlogBundle:Blog:alert.html.twig', array('nom' => 'winzou')));
    }
    public function byeAction()
    {
        return $this->render('SdzBlogBundle:Blog:bye.html.twig',  array('nom' => 'greg'));
    }
    public function voirAction($id)
    {
        // $id vaut 5 si l'on a appelé l'URL /blog/article/5
         
        // Ici, on récupèrera depuis la base de données l'article correspondant à l'id $id
        // Puis on passera l'article à la vue pour qu'elle puisse l'afficher
        return new Response("Affichage de l'article d'id : ".$id.".");
    }
    public function voirSlugAction($slug, $annee, $format)
    {
        var_dump('greg');
        // Ici le contenu de la méthode
        return new Response("On pourrait afficher l'article correspondant au slug '".$slug."', créé en ".$annee." et au format ".$format.".");
    }
}
