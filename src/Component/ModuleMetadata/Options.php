<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 0:07
 */

namespace Aplab\AplabAdminBundle\Component\ModuleMetadata;


use Doctrine\ORM\Mapping\Annotation;

/**
 * Class Options
 * @package Aplab\AplabAdminBundle\Component\ModuleMetadata
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Options implements Annotation
{
    public function __construct(array $values = [])
    {
        foreach ($values as $name => $value) {
            $this->$name = $value;
        }
    }
}