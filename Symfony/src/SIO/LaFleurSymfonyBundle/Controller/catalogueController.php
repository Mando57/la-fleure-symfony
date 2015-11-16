<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace SIO\LaFleurSymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use models;
use Symfony\Component\HttpFoundation\Session\Session;

class catalogueController extends Controller
{
	



    public function indexAction()
    {
        $pdo=models\PdoLafleur::getPdoLafleur();
        $lesCategories = $pdo->getLesCategories();
    	
        $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:catalogue.html.twig',array('lesCategories' => $lesCategories));
		return new Response($content);
    	

    }
    public function categorieAction($cat)
    {
         $pdo=models\PdoLafleur::getPdoLafleur();
        $lesProduits = $pdo->getLesProduitsDeCategorie($cat);
        $lesCategories = $pdo->getLesCategories();
        
        $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:categorie.html.twig',array('lesProduits' => $lesProduits,'lesCategories' => $lesCategories,'laCateg' => $cat));
        return new Response($content);
    }

    public function ajouterPanierAction($cat,$prod)
    {
        $lePanier = $this->container->get('la_fleur_symfony.panier');
        $lePanier->ajouterAuPanier($prod);
        $this->get('session')->getFlashBag()->add('notice','Article correctement mis au panier');
        $url = $this->get('router')->generate('la_fleur_symfony_categorie',array('cat'=>$cat));
        return new RedirectResponse($url);
    }

    public function voirPanierAction(Request $request)
    {
        $lePanier = $this->container->get('la_fleur_symfony.panier');
        $panier=$lePanier->getPanier();
        

        if($this->get('session')->has('logged'))
        {
           $logged=1;
        }else
        {
           $logged=2;
        }

        if($panier!=0)
        {
            $pdo=models\PdoLafleur::getPdoLafleur();
            $lesProduits = $pdo->getLesProduitsDuTableau($panier);
            
            $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:panier.html.twig',array('lePanier'=>$lesProduits,'logged'=>$logged));

        }else{
            
            $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:panier.html.twig');
        }

        if($request->request->has('qte'))
        {
            $produits=$request->request->get('produit');
            $quantite=$request->request->get('quantite');

            $lePanier = $this->container->get('la_fleur_symfony.panier');
            $panier=$lePanier->setQuantites($produits,$quantite);

            $url = $this->get('router')->generate('la_fleur_symfony_validerPanier');
            return new RedirectResponse($url);

        }
        return new Response($content);
    }

    public function retirerPanierAction($id)
    {
        $lePanier = $this->container->get('la_fleur_symfony.panier');
        $panier=$lePanier->removeAt($id);
         $this->get('session')->getFlashBag()->add('notice','Article correctement suprimmer du panier');
        $url = $this->get('router')->generate('la_fleur_symfony_voirPanier');
        return new RedirectResponse($url);
    }

    public function nouveauClientAction(Request $request)
    {
        if($this->get('session')->has('logged'))
        {
            $this->get('session')->getFlashBag()->add('notice','vous deja etes connecter');
            $content = $this->get('templating')->render('LaFleurSymfonyBundle:Default:index.html.twig');

        }else{
            if($request->request->has('creer'))
            {
                $this->get('session')->getFlashBag()->add('notice','compte creer');
                $login=$request->get('login');
                $pwd=$request->get('pwd');
                $pdo=models\PdoLafleur::getPdoLafleur();
                $pdo->createClient($login,$pwd);
                $content = $this->get('templating')->render('LaFleurSymfonyBundle:Default:index.html.twig');

            }else{
                $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:nouveau.html.twig');

            }
        }
        return new Response($content);
    }



    public function connectionClientAction(Request $request)
    {
        if($this->get('session')->has('logged'))
        {
            $this->get('session')->getFlashBag()->add('notice','vous deja etes connecter');
            $content = $this->get('templating')->render('LaFleurSymfonyBundle:Default:index.html.twig');

        }else{
            if($request->request->has('co'))
            {
                $this->get('session')->getFlashBag()->add('notice','vous etes connecter');
                $login=$request->get('login');
                $pwd=$request->get('pwd');
                $pdo=models\PdoLafleur::getPdoLafleur();
                $try=$pdo->checkClient($login,$pwd);
                if($try['logged']==true);
                {

                    $session->set('logged',true);
                    $session->set('userId',$try['id']);
                }
                $content = $this->get('templating')->render('LaFleurSymfonyBundle:Default:index.html.twig');

            }else{
                $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:connec.html.twig');

            }
        }
        return new Response($content);
    }

    public function validPanierAction(Request $request)
    {
       if($this->get('session')->has('logged'))
        {
            if($request->request->has('valider'))
            {
                $lePanier = $this->container->get('la_fleur_symfony.panier');
                $panier=$lePanier->getPanier();
                
                $nom=$request->get('nom');
                $rue=$request->get('rue');
                $cp=$request->get('cp');
                $ville=$request->get('ville');
                $pdo=models\PdoLafleur::getPdoLafleur();
                $pdo->creerCommande($nom,$rue,$cp,$ville,$panier);


                $this->get('session')->getFlashBag()->add('notice','commande valider');
                $content = $this->get('templating')->render('LaFleurSymfonyBundle:Default:index.html.twig');
            }else{
                $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:validPanier.html.twig');
            }

        }else{
            $this->get('session')->getFlashBag()->add('notice','vous n\'etes pas connecter');
            $content = $this->get('templating')->render('LaFleurSymfonyBundle:Catalogue:panier.html.twig');
        }

        return new Response($content);
    }
    
    /*
    public function testAction(Request $request)
    {
		//$url=$this->get('router')->generate('oc_platform_home');
		//return $this->redirect($url);
		/*$url = $this->get('router')->generate('oc_platform_home');
    	return new RedirectResponse($url);
    	 // Récupération de la session
	    $session = $request->getSession();
	    
	    // On récupère le contenu de la variable user_id
	    $userId = $session->get('user_id');

	    // On définit une nouvelle valeur pour cette variable user_id
	    $session->set('user_id', 91);

	    // On n'oublie pas de renvoyer une réponse
	    return new Response("<body>Je suis une page de test, je n'ai rien à dire</body>");
	    	

    }*/

    /*public function viewSlugAction($slug, $year, $_format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$_format."."
        );
    }*/





    
}
?>