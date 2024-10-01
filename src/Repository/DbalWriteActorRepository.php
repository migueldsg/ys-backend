<?php

namespace App\Repository;

use App\Dto\ResponseDto\ActorResponseDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DbalWriteActorRepository implements WriteActorRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @param mixed[] $actorList
     * @throws Exception
     */
    public function insertList(array $actorList): void
    {
        $values = '';
        foreach ($actorList as $index => $actor) {
            if ($index > 0) {
                $values .= ', ';
            }

            $values .= '('.implode(',', [
                $actor['id'],
                "'".$actor['login']."'",
                "'".$actor['url']."'",
                "'".$actor['avatar_url']."'"
            ]).')';
        }

        $sql = <<<SQL
            INSERT INTO actor (id, login, url, avatar_url)
            VALUES $values
            ON CONFLICT (id) DO NOTHING
        SQL;

        $this->connection->executeQuery($sql);
    }
}
