<?php

declare(strict_types=1);

namespace App\Dto\ResponseDto;

use App\Adapter\EventTypeAdapter;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class GithubEventResponseDto
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
        Assert\Type('array'),
        Assert\NotBlank,
    ]
    public readonly mixed $payload;

    #[
        Assert\Type('string'),
        Assert\NotBlank,
        SerializedName('created_at')
    ]
    public readonly mixed $createAt;

    #[Assert\Type(EventAuthorMessageResponseDto::class)]
    public readonly ?EventAuthorMessageResponseDto $author;

    public function __construct(
        mixed $id,
        mixed $type,
        ActorResponseDto $actor,
        RepoResponseDto $repo,
        mixed $payload,
        mixed $createAt,
        EventAuthorMessageResponseDto $author = null
    )
    {
        $this->id = intval($id);
        $this->type = EventTypeAdapter::adapt($type);
        $this->actor = $actor;
        $this->repo = $repo;
        $this->payload = $payload;
        $this->createAt = $createAt;
        $this->author = $author;
    }
}
