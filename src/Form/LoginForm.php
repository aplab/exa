<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 01.09.2018
 * Time: 14:25
 */

namespace Aplab\AplabAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username')
            ->add('_password', PasswordType::class);
    }
}