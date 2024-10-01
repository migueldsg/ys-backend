<?php

declare(strict_types=1);

namespace App\ValueObject;

class GithubEventTypeValueObject
{
    public const PUSH_EVENT = 'PushEvent';
    public const PULL_REQUEST_EVENT = 'PullRequestEvent';
    public const ISSUE_COMMENT_EVENT = 'IssueCommentEvent';
    public const COMMIT_COMMENT_EVENT = 'CommitCommentEvent';
    public const PULL_REQUEST_REVIEW_COMMENT_EVENT = 'PullRequestReviewCommentEvent';

    /**
     * @return string[]
     */
    public static function getConstantsValue(): array
    {
        return array_values((new \ReflectionClass(self::class))->getConstants());
    }
}
