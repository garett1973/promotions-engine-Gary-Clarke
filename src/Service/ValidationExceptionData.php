<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionData extends ServiceExceptionData
{
    public function __construct(int $statusCode, string $type, ConstraintViolationList $violationList)
    {
        parent::__construct($statusCode, $type);
        $this->violationList = $violationList;
    }

    public function toArray(): array
    {
        return [
            'type' => 'ConstraintViolationList',
            'violations' => $this->getViolationsArray(),
        ];
    }

    public function getViolationsArray(): array
    {
        $violationsList = [];

//        dd($this->violationList);

        foreach ($this->violationList as $violation) {
            $violationsList[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $violationsList;
    }
}
