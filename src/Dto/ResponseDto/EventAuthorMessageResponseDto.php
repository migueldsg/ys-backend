<?php

declare(strict_types=1);

namespace App\Dto\ResponseDto;

use Symfony\Component\Validator\Constraints as Assert;

class EventAuthorMessageResponseDto
{
    #[Assert\Type('string')]
    public readonly mixed $message;

    public function __construct(mixed $message)
    {}
}
