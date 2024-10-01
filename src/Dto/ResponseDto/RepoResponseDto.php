<?php

declare(strict_types=1);

namespace App\Dto\ResponseDto;

use Symfony\Component\Validator\Constraints as Assert;

class RepoResponseDto
{
    #[
        Assert\Type('integer'),
        Assert\NotBlank,
    ]
    public readonly mixed $id;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
    ]
    public readonly mixed $name;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
    ]
    public readonly mixed $url;
    public function __construct(mixed $id, mixed $name, mixed $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }
}
