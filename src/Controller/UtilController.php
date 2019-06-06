<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 20.08.2018
 * Time: 22:44
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Util\CssWidthDefinition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UtilController
 * @package Aplab\AplabAdminBundle\Controller
 */
class UtilController extends Controller
{
    /**
     * @param CssWidthDefinition $cwd
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cssWidthDefinition(CssWidthDefinition $cwd)
    {
        return $this->render('@AplabAdmin/css-width-definition.html.twig', [
            'w' => $cwd->getData(),
            's' => $cwd->getSum(),
            'e' => $cwd->getSum() + 100
        ]);
    }
}