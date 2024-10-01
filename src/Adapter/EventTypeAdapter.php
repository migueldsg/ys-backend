<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Entity\EventType;
use App\ValueObject\GithubEventTypeValueObject;

class EventTypeAdapter
{
    private const EVENT_TYPE_MAPPING_ARRAY = [
        GithubEventTypeValueObject::PUSH_EVENT => EventType::COMMIT,
        GithubEventTypeValueObject::PULL_REQUEST_EVENT => EventType::PULL_REQUEST,
        GithubEventTypeValueObject::ISSUE_COMMENT_EVENT => EventType::COMMENT,
        GithubEventTypeValueObject::COMMIT_COMMENT_EVENT => EventType::COMMENT,
        GithubEventTypeValueObject::PULL_REQUEST_REVIEW_COMMENT_EVENT => EventType::COMMENT
    ];

    public static function adapt(?string $importType = null): ?string
    {
        if (false === array_key_exists($importType, self::EVENT_TYPE_MAPPING_ARRAY)) {
            return null;
        }

        return self::EVENT_TYPE_MAPPING_ARRAY[$importType];
    }
}
