<?php

namespace App\EventSubscriber;

use App\Event\AfterDtoCreatedEvent;
use App\Service\ServiceException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{

    public function __construct(private ValidatorInterface $validator)
    {
    }



    public static function getSubscribedEvents(): array
    {
        return [
            AfterDtoCreatedEvent::NAME => [
                    [
                        'validateDto', 100
                    ],
//                    [
//                        'doSomethingElse', 1 // setting additional action with priority 1 (lowest) for the same event
//                    ],
                ]
        ];
    }

    public function validateDto(AfterDtoCreatedEvent $event): void
    {
        // Validate DTO
        $dto = $event->getDto();
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new ServiceException(422, "Validation failed ");
        }
    }

//    public function doSomethingElse(AfterDtoCreatedEvent $event)
//    {
//        dd('doing something else');
//    }
}