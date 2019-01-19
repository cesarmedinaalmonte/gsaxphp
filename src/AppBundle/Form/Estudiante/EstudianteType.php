<?php

namespace AppBundle\Form\Estudiante;

use AppBundle\Entity\Estudiante\Estudiante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstudianteType extends AbstractType
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
            ->add('padre')
            ->add('madre');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Estudiante::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ));
    }


}
