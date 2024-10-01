<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Dto\ResponseDto\EventResponseDto;
use App\Entity\EventType;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GithubEventImportAdapter
{
    public function __construct() {}

    /**
     * @return mixed[]
     * @throws \Exception
     */
    public function adaptZippedEventIntoArray(string $zippedEventFileContent, SymfonyStyle $io): array
    {
        $fileName = time().'.json.gz';
        file_put_contents($fileName, $zippedEventFileContent);

        $fileContent = gzfile($fileName);
        if (false === $fileContent) {
            throw new \Exception();
        }

        $progressBar = new ProgressBar($io, count($fileContent));
        $eventList = [];
        foreach ($fileContent as $event) {
            $event = (array) json_decode($event, true);
            if (false === array_key_exists('repo', $event)
                || false === array_key_exists('actor', $event)
            ) {
                continue;
            }

            $adaptedType = EventTypeAdapter::adapt($event['type']);
            if (null === $adaptedType) {
                continue;
            }

            $event['type'] = $adaptedType;
            $event['count'] = EventType::COMMIT === $adaptedType ? $event['payload']['size'] : 1;
            $event['payload'] = json_encode($event['payload']);

            $eventList[] = $event;
            $progressBar->advance();
        }

        $progressBar->finish();
        unlink($fileName);

        return $eventList;
    }
}
