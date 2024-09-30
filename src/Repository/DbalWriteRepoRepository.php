<?php

namespace App\Repository;

use App\Dto\ResponseDto\RepoResponseDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DbalWriteRepoRepository implements WriteRepoRepository
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * @param RepoResponseDto[] $repoList
     * @throws Exception
     */
    public function insertList(array $repoList): void
    {
        $values = '';
        foreach ($repoList as $index => $repo) {
            if ($index > 0) {
                $values .= ', ';
            }

            $values .= '('.implode(',', [$repo->id, "'".$repo->name."'", "'".$repo->url."'"]).')';
        }

        $sql = <<<SQL
            INSERT INTO repo (id, name, url)
            VALUES $values
            ON CONFLICT (id) DO NOTHING
        SQL;

        $this->connection->executeQuery($sql);
    }
}
