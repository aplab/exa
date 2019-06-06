<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 27.08.2018
 * Time: 11:26
 */

namespace Aplab\AplabAdminBundle\Form;


class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task')
            ->add('dueDate', null, array('widget' => 'single_text'))
            ->add('save', SubmitType::class)
        ;
    }
}