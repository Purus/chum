<?php
namespace Chum\Core\Controllers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class InstallAdminConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Admin Username",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('password', PasswordType::class, [
                'label' => "Admin Password",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('email', EmailType::class, [
                'label' => "Admin Email",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('firstName', TextType::class, [
                'label' => "Firstname",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('lastName', TextType::class, [
                'label' => "Lastname",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Finish Setup',
                "attr" => ["class" => "btn btn-primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstallAdminConfig::class,
            'attr' =>   ["class" => "w-full"]
        ]);

    }
}