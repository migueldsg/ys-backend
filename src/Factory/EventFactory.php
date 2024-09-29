<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\ResponseDto\GithubEventResponseDto;
use App\Entity\Event;
use DateTimeImmutable;

class EventFactory
{
    /**
     * @throws \DateMalformedStringException
     */
    public static function create(GithubEventResponseDto $eventDto): Event
    {
        return new Event(
            id: $eventDto->id,
            type: $eventDto->type,
            actor: ActorFactory::create($eventDto->actor),
            repo: RepoFactory::create($eventDto->repo),
            payload: $eventDto->payload,
            createAt: new DateTimeImmutable($eventDto->createAt),
            comment: $eventDto?->author?->message
        );
    }
}
