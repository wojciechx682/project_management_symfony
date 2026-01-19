<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            /*->add('createdAt', null, [
                'widget' => 'single_text',
            ])*/
            /*->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
            /*->add('leader', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])*/
            ->add('leader', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $u) => $u->getFirstName().' '.$u->getLastName(),
                'query_builder' => fn(UserRepository $ur) => $ur->createQueryBuilder('u')
                    ->andWhere('u.role = :role')
                    ->setParameter('role', 'ROLE_PM')
                    ->orderBy('u.lastName', 'ASC'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
