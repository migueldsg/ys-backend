<?php

namespace App\Service;

use App\Adapter\GithubEventImportAdapter;
use App\Dto\CommandParamDto\ImportGithubEventsCommandParamDto;
use App\HttpClient\GHArchiveHttpClient;
use App\Repository\WriteActorRepository;
use App\Repository\WriteEventRepository;
use App\Repository\WriteRepoRepository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GithubEventService
{
    public function __construct(
        public readonly GHArchiveHttpClient $ghArchiveHttpClient,
        public readonly GithubEventImportAdapter $githubEventImportAdapter,
        public readonly WriteRepoRepository $writeRepoRepository,
        public readonly WriteActorRepository $writeActorRepository,
        public readonly WriteEventRepository $writeEventRepository,
    ) {}

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function importEvents(ImportGithubEventsCommandParamDto $commandParamDto, OutputInterface $output): void
    {
        for ($i = $commandParamDto->startHour; $i <= $commandParamDto->endHour; $i++) {
            $date = $commandParamDto->date->format('Y-m-d');

            $zippedEventFileContent = $this->ghArchiveHttpClient->getEventsByDate($date, $i);
            $eventDtoList = $this->githubEventImportAdapter->adaptZippedEventFileIntoDto($zippedEventFileContent, $output);

            $repoBatch = [];
            $actorBatch = [];
            $eventBatch = [];
            $progressBar = new ProgressBar($output, count($eventDtoList));
            foreach ($eventDtoList as $eventDto) {
                $progressBar->advance();

                $repoBatch[] = $eventDto->repo;
                $actorBatch[] = $eventDto->actor;
                $eventBatch[] = $eventDto;

                if (count($eventBatch) >= 100) {
                    $this->writeRepoRepository->insertList($repoBatch);
                    $this->writeActorRepository->insertList($actorBatch);
                    $this->writeEventRepository->insertList($eventBatch);

                    $repoBatch = [];
                    $actorBatch = [];
                    $eventBatch = [];        
                }
            }
            $this->writeRepoRepository->insertList($repoBatch);
            $this->writeActorRepository->insertList($actorBatch);
            $this->writeEventRepository->insertList($eventBatch);

            $progressBar->finish();
        }
    }
}
