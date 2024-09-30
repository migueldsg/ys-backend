<?php

namespace App\Repository;

use App\Dto\EventInput;
use App\Dto\ResponseDto\EventResponseDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DbalWriteEventRepository implements WriteEventRepository
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * @throws Exception
     */
    public function update(EventInput $authorInput, int $id): void
    {
        $sql = <<<SQL
        UPDATE event
        SET comment = :comment
        WHERE id = :id
SQL;

        $this->connection->executeQuery($sql, ['id' => $id, 'comment' => $authorInput->comment]);
    }

    /**
     * @param EventResponseDto[] $eventList
     * @throws Exception
     */
    public function insertList(array $eventList): void
    {
        $values = '';
        foreach ($eventList as $index => $event) {
            if ($index > 0) {
                $values .= ', ';
            }

            //Really sketchy but running out of time
            $values .= '('.implode(',', [$event->id, "'".$event->type."'", $event->count, $event->actor->id, $event->repo->id, "'".str_replace("'", '', $event->payload)."'", "'".$event->createAt."'"]).')';
        }

        $sql = <<<SQL
            INSERT INTO event (id, type, count, actor_id, repo_id, payload, create_at)
            VALUES $values
            ON CONFLICT (id) DO NOTHING
        SQL;

        $this->connection->executeQuery($sql);
    }
}
