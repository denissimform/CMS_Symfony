<?php

namespace App\Form;

use App\Entity\SubscriptionDuration;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class SubscriptionType extends AbstractType
{
    private $choices, $data;

    public function __construct(
        private SubscriptionRepository $subscriptionRepository,

    ) {
        $this->choices = $this->subscriptionRepository->findTypes();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $data = json_decode($options['customData'], 1) ?? null;

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => $this->choices,
                'label' => 'Subscription Plan',
                'placeholder' => 'Select Type',
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Please select Subscription Plan.')
                ],
                'data' => $data['type'] ?? ''
            ])
            ->add('subscription_id', HiddenType::class)
            ->add('criteria_dept', IntegerType::class, [
                'label' => 'Max Departments',
                'required' => true,
            ])
            ->add('criteria_user', IntegerType::class, [
                'label' => 'Max User',
                'required' => true,
            ])
            ->add('criteria_storage', IntegerType::class, [
                'label' => 'Max Storage',
                'required' => true,
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration',
                'help' => 'in months only',
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'This field should not be blank.'),
                    new Range(
                        min: 1,
                        max: 24,
                        notInRangeMessage: 'Duration must be between {{ min }} and {{ max }}.',
                    )
                ],
                'data' => $data['duration'] ?? 0
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Price',
                'help' => 'in Rs. only',
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'This field should not be blank.'),
                    new Range(
                        min: 10,
                        max: 1000000,
                        notInRangeMessage: 'Price must be between {{ min }} and {{ max }}.',
                    )
                ],
                'data' => $data['price'] ?? 0
            ])
            ->add('customType', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'customData' => null
        ]);
    }
}
