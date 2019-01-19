<?php

namespace AppBundle\Form\Docente;

use AppBundle\Entity\Docente\Docente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('sexo')
            ->add('direccion')
            ->add('telefono')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Docente::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ));
    }


}
