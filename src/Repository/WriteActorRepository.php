<?php

namespace App\Repository;

use App\Dto\ResponseDto\ActorResponseDto;

interface WriteActorRepository
{
    /**
     * @param ActorResponseDto[] $actorList
     */
    public function insertList(array $actorList): void;
}
