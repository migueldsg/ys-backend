<?php

declare(strict_types=1);

namespace App\Dto\ResponseDto;

use App\Adapter\EventTypeAdapter;
use App\Entity\EventType;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class EventResponseDto
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
    public readonly mixed $type;

    #[
        Assert\Type('integer'),
        Assert\NotBlank,
    ]
    public readonly mixed $count;

    #[
        Assert\Type(ActorResponseDto::class),
        Assert\NotBlank,
    ]
    public readonly ActorResponseDto $actor;

    #[
        Assert\Type(RepoResponseDto::class),
        Assert\NotBlank,
    ]
    public readonly RepoResponseDto $repo;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
    ]
    public readonly mixed $payload;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
        SerializedName('created_at')
    ]
    public readonly mixed $createAt;

    public function __construct(
        mixed $id,
        mixed $type,
        ActorResponseDto $actor,
        RepoResponseDto $repo,
        mixed $payload,
        mixed $createAt,
        mixed $count = 1
    )
    {
        $this->id = intval($id);
        $this->type = EventTypeAdapter::adapt($type);
        $this->actor = $actor;
        $this->repo = $repo;
        $this->payload = json_encode($payload);
        $this->createAt = $createAt;
        $this->count = EventType::COMMIT === $this->type ? $payload['size'] : 1;
    }
}
