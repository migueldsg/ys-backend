<?php

namespace App\Repository;

use App\Dto\ResponseDto\RepoResponseDto;

interface WriteRepoRepository
{
    /**
     * @param RepoResponseDto[] $repoList
     */
    public function insertList(array $repoList): void;
}
