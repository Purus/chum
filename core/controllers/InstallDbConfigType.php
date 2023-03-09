<?php
namespace Chum\Core\Controllers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstallDbConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dbHost', TextType::class, [
            'label' => "Database Hostname",
            'required' => true,
            'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
            'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
            'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
            'constraints' => new NotBlank(),
        ])
        ->add('dbUser', TextType::class, [
            'label' => "Database Username",
            'required' => true,
            'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
            'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
            'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
            'constraints' => new NotBlank(),
        ])
        ->add('dbPassword', PasswordType::class, [
            'label' => "Database Password",
            'required' => true,
            'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
            'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
            'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
        ])
        ->add('dbPrefix', TextType::class, [
            'label' => "Table Prefix",
            'required' => false,
            'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
            'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
            'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Setup Database',
            "attr" => ["class" => "btn btn-primary"]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstallDbConfig::class,
            'attr' =>   ["class" => "w-full"]
        ]);
    }
}