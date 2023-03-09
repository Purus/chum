<?php
namespace Chum\Core\Controllers;

use Chum\Core\ValidDirectory;
use Chum\Core\ValidRootDirectory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstallAppConfigType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('language', LocaleType::class, [
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'label' => "Website Language",
                'choice_loader' => null,
                'choices' => [
                    'English' => 'en',
                    'French' => "fr",
                ],
            ])
            ->add('siteUrl', UrlType::class, [
                'label' => "Site URL",
                'default_protocol' => 'https',
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => [new NotBlank()]
            ])
            ->add('tagLine', TextType::class, [
                'label' => "Catchy Tagline",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('siteName', TextType::class, [
                'label' => "Awesome Site Name",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new NotBlank(),
            ])
            ->add('rootPath', TextType::class, [
                'label' => "Installation Root Path",
                'required' => true,
                'row_attr' => ["class" => "w-full md:w-1/2 px-3 mb-3"],
                'attr' => ["class" => "appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"],
                'label_attr' => ["class" => "block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"],
                'constraints' => new ValidDirectory(),
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Setup Network',
                "attr" => ["class" => "btn btn-primary"]
            ]);
            
            // TODO: use form event to verify the root dir validation with DI
            // $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);

    }

    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstallAppConfig::class,
            'attr' =>   ["class" => "w-full"]
        ]);
    }
}