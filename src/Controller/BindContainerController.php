<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace App\Controller;


use App\Entity\Bind\Container;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NamedTimestampableController
 * @package App\Controller
 * @Route("/bind-container", name="bind_container_")
 */
class BindContainerController extends ReferenceController
{
    /**
     * @var string
     */
    protected $entityClassName = Container::class;
}
