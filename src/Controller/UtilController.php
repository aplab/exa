<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 20.08.2018
 * Time: 22:44
 */

namespace App\Controller;


use App\Util\CssWidthDefinition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UtilController
 * @package App\Controller
 */
class UtilController extends AbstractController
{
    /**
     * @param CssWidthDefinition $cwd
     * @return Response
     */
    public function cssWidthDefinition(CssWidthDefinition $cwd)
    {
        /** @noinspection PhpParamsInspection */
        return $this->render('css-width-definition.html.twig', [
            'w' => $cwd->getData(),
            's' => $cwd->getSum(),
            'e' => $cwd->getSum() + 100
        ]);
    }
}
