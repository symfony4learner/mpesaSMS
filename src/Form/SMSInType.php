<?php

namespace App\Form;

use App\Entity\SMSIn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SMSInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('confirmation_code')
            ->add('client_name')
            ->add('phone_number')
            ->add('amount_received')
            ->add('received_on')
            ->add('sms_origin')
            ->add('whole_sms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SMSIn::class,
        ]);
    }
}
