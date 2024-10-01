<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\ResponseDto\RepoResponseDto;
use App\Entity\Repo;

class RepoFactory
{
    public static function create(RepoResponseDto $repoDto): Repo
    {
        return new Repo(
            id: $repoDto->id,
            name: $repoDto->name,
            url: $repoDto->url
        );
    }
}
