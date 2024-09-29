<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Dto\ResponseDto\GithubEventResponseDto;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GithubEventImportAdapter
{
    public function __construct(
        public readonly SerializerInterface $serializer,
        public readonly ValidatorInterface $validator,
    ) {}

    /**
     * @return GithubEventResponseDto[]
     * @throws \Exception
     */
    public function adaptZippedEventFileIntoDto(string $zippedEventFileContent, OutputInterface $output): array
    {
        $fileName = time().'.json.gz';

        file_put_contents($fileName, $zippedEventFileContent);
        $file = gzfile($fileName);
        if (false === $file) {
            throw new \Exception();
        }

        $progressBar = new ProgressBar($output, count($file));
        $githubEventDtoList = [];
        foreach ($file as $event) {
            $eventArray = (array) json_decode($event);

            if (false === array_key_exists('repo', $eventArray)
                || false === array_key_exists('actor', $eventArray)
            ) {
                continue;
            }

            $eventDto = $this->serializer->deserialize(
                json_decode(json_encode($event), true),
                GithubEventResponseDto::class,
                'json'
            );

            if (null === $eventDto->type) {
                continue;
            }

            $violationList = $this->validator->validate($githubEventDtoList);
            if (0 < count($violationList)) {
                continue;
            }

            $githubEventDtoList[] = $eventDto;
            $progressBar->advance();
        }

        $progressBar->finish();
        unlink($fileName);
        return $githubEventDtoList;
    }
}
