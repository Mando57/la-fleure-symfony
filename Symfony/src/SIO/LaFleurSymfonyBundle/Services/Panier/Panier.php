<?php

namespace SIO\LaFleurSymfonyBundle\Services\Panier;
use Symfony\Component\HttpFoundation\Session\Session;

class Panier
{
	function ajouterAuPanier($produit)
    {

        $session= new Session();
        if($session->has('produits'))
        {
            $lesProduits=$session->get('produits');
        }else{
            $lesProduits=array();
        }
        if(!in_array($produit,$lesProduits))
        {
            $lesProduits[]=$produit;
        }
        $session->set('produits',$lesProduits);

    }
    function getPanier()
    {
        $session= new Session();
        if($session->has('produits'))
        {
            $lesProduits=$session->get('produits');
        }else{
            $lesProduits=0;
        }

       return $lesProduits;
    }

    function setQuantites($produits,$quantite)
    {
        $session= new Session();
        for($i=0;$i<count($produits);$i++)
        {
            $session->set($produits[$i],$quantite[$i]);
        }
        
    }

    function removeAt($id)
    {
        $session=new Session();
        $produits=$session->get('produits');
        $fin=array();
        for($i=0;$i<count($produits);$i++)
        {
            if($produits[$i]==$id)
            {

            }else{
                $fin[]=$produits[$i];
                if($session->has($id))
                {
                    $session->remove($id);
                }
            }
        }
        $session->set('produits',$fin);
    }
}