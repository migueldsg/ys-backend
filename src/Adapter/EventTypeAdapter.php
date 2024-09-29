<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Entity\EventType;

class EventTypeAdapter
{
    private const EVENT_TYPE_MAPPING_ARRAY = [
        'PushEvent' => EventType::COMMIT,
        'PullRequestEvent' => EventType::PULL_REQUEST,
        'IssueCommentEvent' => EventType::COMMENT,
        'CommitCommentEvent' => EventType::COMMENT,
        'PullRequestReviewCommentEvent' => EventType::COMMENT
    ];

    public static function adapt(?string $importType = null): ?string
    {
        if (false === array_key_exists($importType, self::EVENT_TYPE_MAPPING_ARRAY)) {
            return null;
        }

        return self::EVENT_TYPE_MAPPING_ARRAY[$importType];
    }
}
