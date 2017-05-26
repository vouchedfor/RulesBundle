<?php

namespace VouchedFor\RulesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VouchedForRulesBundle:Default:index.html.twig');
    }
}
