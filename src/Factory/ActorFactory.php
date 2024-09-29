<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\ResponseDto\ActorResponseDto;
use App\Entity\Actor;

class ActorFactory
{
    public static function create(ActorResponseDto $actorDto): Actor
    {
        return new Actor(
            id: $actorDto->id,
            login: $actorDto->login,
            url: $actorDto->url,
            avatarUrl: $actorDto->avatarUrl
        );
    }
}
