<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Entity\Bind\Container;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NamedTimestampableController
 * @package Aplab\AplabAdminBundle\Controller
 * @Route("/admin/bind-container", name="admin_bind_container_")
 */
class BindContainerController extends ReferenceAdminController
{
    /**
     * @var string
     */
    protected $entityClassName = Container::class;
}