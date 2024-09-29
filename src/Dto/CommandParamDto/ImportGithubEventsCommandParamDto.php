<?php

declare(strict_types=1);

namespace App\Dto\CommandParamDto;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ImportGithubEventsCommandParamDto
{
    #[
        Assert\Type('\DateTimeImmutable'),
        Assert\NotBlank
    ]
    public readonly mixed $date;

    #[
        Assert\Type('integer'),
        Assert\Range(
            min: 0, max: 23
        ),
    ]
    public readonly mixed $startHour;

    #[
        Assert\Type('integer'),
        Assert\Range(
            min: 0, max: 23
        ),
    ]
    public readonly mixed $endHour;

    public function __construct(mixed $date, mixed $startHour = 0, mixed $endHour = 23)
    {
        $this->date = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        $this->startHour = intval($startHour);
        $this->endHour = intval($endHour);
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->endHour < $this->startHour) {
            $context->buildViolation('End hour cannot be inferior than start hour.')
                ->atPath('endHour')
                ->addViolation();
        }
    }
}
