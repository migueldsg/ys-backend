<?php

declare(strict_types=1);

namespace App\Dto\ResponseDto;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ActorResponseDto
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
    public readonly mixed $login;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
    ]
    public readonly mixed $url;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
        SerializedName('avatar_url')
    ]
    public readonly mixed $avatarUrl;

    public function __construct(mixed $id, mixed $login, mixed $url, mixed $avatarUrl)
    {
        $this->id = $id;
        $this->login = $login;
        $this->url = $url;
        $this->avatarUrl = $avatarUrl;
    }
}
