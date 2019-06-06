<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Entity\Bind\Contained;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NamedTimestampableController
 * @package Aplab\AplabAdminBundle\Controller
 * @Route("/admin/bind-contained", name="admin_bind_contained_")
 */
class BindContainedController extends ReferenceAdminController
{
    /**
     * @var string
     */
    protected $entityClassName = Contained::class;
}