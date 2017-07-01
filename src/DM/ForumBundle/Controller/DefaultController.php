<?php

namespace DM\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DMForumBundle:Default:index.html.twig');
    }
}
