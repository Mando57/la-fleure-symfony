<?php

namespace SIO\LaFleurSymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LaFleurSymfonyBundle:Default:index.html.twig', array('name' => $name));
    }

    public function accueilAction()
    {
    	return $this->render('LaFleurSymfonyBundle:Default:index.html.twig');
    }
}
