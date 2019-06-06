<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 06.08.2018
 * Time: 10:31
 */

namespace Aplab\AplabAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package Aplab\AplabAdminBundle\Controller
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="desktop")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desktop() {
        //$this->addFlash('notice', 'test message');
        return $this->render('@AplabAdmin/admin.html.twig', get_defined_vars());
    }
}