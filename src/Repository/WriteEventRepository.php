<?php

namespace App\Repository;

use App\Dto\EventInput;
use App\Dto\ResponseDto\EventResponseDto;

interface WriteEventRepository
{
    public function update(EventInput $authorInput, int $id): void;

    /**
     * @param mixed[] $eventList
     */
    public function insertList(array $eventList): void;
}
