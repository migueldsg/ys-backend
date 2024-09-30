<?php

declare(strict_types=1);

namespace App\ValueObject;

class EventImportFilePathValueObject
{
    public const ACTOR_IMPORT_FILE_PATH = '{date}_{hour}_actor_import.csv';
    public const REPO_IMPORT_FILE_PATH = '{date}_{hour}_repo_import.csv';
    public const EVENT_IMPORT_FILE_PATH = '{date}_{hour}_event_import.csv';

    public static function getActorImportFilePath(string $date, string $hour): string
    {
        return str_replace(['{date}', '{hour}'], [$date, $hour], self::ACTOR_IMPORT_FILE_PATH);
    }

    public static function getRepoImportFilePath(string $date, string $hour): string
    {
        return str_replace(['{date}', '{hour}'], [$date, $hour], self::REPO_IMPORT_FILE_PATH);
    }

    public static function getEventImportFilePath(string $date, string $hour): string
    {
        return str_replace(['{date}', '{hour}'], [$date, $hour], self::EVENT_IMPORT_FILE_PATH);
    }
}
