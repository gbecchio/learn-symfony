<?php
// src/Sdz/BlogBundle/Controller/BlogController.php
namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
// Attention à bien ajouter ce use en début de contrôleur
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Entity\Image;
use Sdz\BlogBundle\Entity\Commentaire;

class BlogController extends Controller
{
    public function indexAction()
    {
      $doctrine = $this->getDoctrine();
      $doctrine2 = $this->get('doctrine');
      //var_dump($doctrine = $this->getDoctrine());
      $em = $doctrine->getManager();
      $repository_article = $em->getRepository('SdzBlogBundle:Article');
      $repository_article2 = $em->getRepository('Sdz\BlogBundle\Entity\Article');
      
      //var_dump($repository_article);
      // …
      $mailer = $this->container->get('mailer'); // On a donc accès au conteneur
      $mailer2 = $this->get('mailer'); // On a donc accès au conteneur
      $antispam = $this->container->get('sdz_blog.antispam');
      $param = $this->container->getParameter('mon_parametre');
      $txt = <<<txt
<a href='http://www.google.fr>a</a><br />
<a href='http://www.google.fr>a</a><br />
txt;

     /* echo "<pre>";
      print_r($mailer);
      print_r($mailer2);
      var_dump($antispam->isSpam($txt));
      echo $param;
      echo "</pre>";
      */
       // Je pars du principe que $text contient le texte d'un message quelconque
    if ($antispam->isSpam($txt)) {
      throw new \Exception('Votre message a été détecté comme spam !');
    }
      // Les articles :
      $articles = array(
        array(
          'titre'   => 'Mon weekend a Phi Phi Island !',
          'id'      => 1,
          'auteur'  => 'winzou',
          'contenu' => 'Ce weekend était trop bien. Blabla…',
          'date'    => new \Datetime()),
        array(
          'titre'   => 'Repetition du National Day de Singapour',
          'id'      => 2,
          'auteur' => 'winzou',
          'contenu' => 'Bientôt prêt pour le jour J. Blabla…',
          'date'    => new \Datetime()),
        array(
          'titre'   => 'Chiffre d\'affaire en hausse',
          'id'      => 3, 
          'auteur' => 'M@teo21',
          'contenu' => '+500% sur 1 an, fabuleux. Blabla…',
          'date'    => new \Datetime())
      );
        
      // Puis modifiez la ligne du render comme ceci, pour prendre en compte nos articles :
      return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
        'articles' => $articles
      ));
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
      // On récupère l'EntityManager
      $em = $this->getDoctrine()
                 ->getManager();

      // On récupère l'entité correspondant à l'id $id
      $article = $em->getRepository('SdzBlogBundle:Article')
                    ->find($id);

      if($article === null)
      {
        throw $this->createNotFoundException('Article[id='.$id.'] inexistant.');
      }

      // On récupère la liste des commentaires
      $liste_commentaires = $em->getRepository('SdzBlogBundle:Commentaire')
                               ->findAll();

      // Puis modifiez la ligne du render comme ceci, pour prendre en compte l'article :
      return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
        'article'      => $article,
        'liste_commentaires' => $liste_commentaires
      ));
    }
    public function voirSlugAction($slug, $annee, $format)
    {
        var_dump('greg');
        // Ici le contenu de la méthode
        return new Response("On pourrait afficher l'article correspondant au slug '".$slug."', créé en ".$annee." et au format ".$format.".");
    }
    public function ajouterAction()
    {
      // Création de l'entité Article
      $article = new Article();
      $article->setTitre('Mon dernier weekend');
      $article->setContenu("C'était vraiment super et on s'est bien amusé.");
      $article->setAuteur('winzou');

      // Création d'un premier commentaire
      $commentaire1 = new Commentaire();
      $commentaire1->setAuteur('winzou');
      $commentaire1->setContenu('On veut les photos !');

      // Création d'un deuxième commentaire, par exemple
      $commentaire2 = new Commentaire();
      $commentaire2->setAuteur('Choupy');
      $commentaire2->setContenu('Les photos arrivent !');

      // On lie les commentaires à l'article
      $commentaire1->setArticle($article);
      $commentaire2->setArticle($article);

      // On récupère l'EntityManager
      $em = $this->getDoctrine()->getManager();

      // Étape 1 : On persiste les entités
      $em->persist($article);
      // Pour cette relation pas de cascade, car elle est définie dans l'entité Commentaire et non Article
      // On doit donc tout persister à la main ici
      $em->persist($commentaire1);
      $em->persist($commentaire2);

      // Étape 2 : On déclenche l'enregistrement
      $em->flush();
      
      // Reste de la méthode qu'on avait déjà écrit
      if ($this->getRequest()->getMethod() == 'POST') {
        $this->get('session')->getFlashBag()->add('info', 'Article bien enregistré');
        return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())) );
      }


      return $this->render('SdzBlogBundle:Blog:ajouter.html.twig');
    }
    public function menuAction($nombre) // Ici, nouvel argument $nombre, on l'a transmis via le render() depuis la vue
    {
        // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
        // On pourra récupérer $nombre articles depuis la BDD,
        // avec $nombre un paramètre qu'on peut changer lorsqu'on appelle cette action
        $liste = array
        (
            array('id' => 2, 'titre' => 'Mon dernier weekend !'),
            array('id' => 5, 'titre' => 'Sortie de Symfony2.1'),
            array('id' => 9, 'titre' => 'Petit test')
        );
        return $this->render('SdzBlogBundle:Blog:menu.html.twig',
            array
            (
                'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
            )
        );
    }
    
  public function modifierAction($id)
  {
     // On récupère l'EntityManager
    $em = $this->getDoctrine()
               ->getManager();

    // On récupère l'entité correspondant à l'id $id
    $article = $em->getRepository('SdzBlogBundle:Article')
                  ->find($id);

    if ($article === null) {
      throw $this->createNotFoundException('Article[id='.$id.'] inexistant.');
    }

    // On récupère toutes les catégories :
    $liste_categories = $em->getRepository('SdzBlogBundle:Categorie')
                           ->findAll();

    // On boucle sur les catégories pour les lier à l'article
    foreach($liste_categories as $categorie)
    {
      $article->addCategorie($categorie);
    }

    // Inutile de persister l'article, on l'a récupéré avec Doctrine

    // Étape 2 : On déclenche l'enregistrement
    $em->flush();

    return new Response('OK');
  }
  public function modifierImageAction($id_article)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'article
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id_article);
    $article->getImage();
    // On modifie l'URL de l'image par exemple
    $article->getImage()->setUrl('https://lh4.googleusercontent.com/-TtZ8a2i9KsM/UYFKqR5KewI/AAAAAAAABiI/VvuOY_VEJQc/w1600-h900/default_cover_2_ae125d34a6150400a2a97f22e218a904.jpg');
    $image = $article->getImage();
    // On n'a pas besoin de persister notre article (si vous le faites, aucune erreur n'est déclenchée, Doctrine l'ignore)
    // Rappelez-vous, il l'est automatiquement car on l'a récupéré depuis Doctrine

    // Pas non plus besoin de persister l'image ici, car elle est également récupérée par Doctrine

    // On déclenche la modification
    $em->flush();

    return $this->render('SdzBlogBundle:Blog:modifierImage.html.twig', array(
      'image' => $image, 'id_article' => $id_article, 'article' => $article
    ));
  }
}
