<?php

namespace App\Service;

use App\Adapter\GithubEventImportAdapter;
use App\Dto\CommandParamDto\ImportGithubEventsCommandParamDto;
use App\HttpClient\GHArchiveHttpClient;
use App\Repository\WriteActorRepository;
use App\Repository\WriteEventRepository;
use App\Repository\WriteRepoRepository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GithubEventService
{
    public function __construct(
        public readonly GHArchiveHttpClient $ghArchiveHttpClient,
        public readonly GithubEventImportAdapter $githubEventImportAdapter,
        public readonly WriteRepoRepository $writeRepoRepository,
        public readonly WriteActorRepository $writeActorRepository,
        public readonly WriteEventRepository $writeEventRepository,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function importEvents(ImportGithubEventsCommandParamDto $commandParamDto, SymfonyStyle $io): void
    {
        for ($i = $commandParamDto->startHour; $i <= $commandParamDto->endHour; $i++) {
            $date = $commandParamDto->date->format('Y-m-d');
            $io->section("Importing Github Event : ".$date." ".$i);

            $zippedEventFileContent = $this->ghArchiveHttpClient->getEventsByDate($date, $i);
            $eventList = $this->githubEventImportAdapter->adaptZippedEventIntoArray($zippedEventFileContent, $io);
            unset($zippedEventFileContent);

            $repoBatch = [];
            $actorBatch = [];
            $eventBatch = [];
            $progressBar = new ProgressBar($io, count($eventList));
            foreach ($eventList as $event) {
                $progressBar->advance();

                $repoBatch[] = $event['repo'];
                $actorBatch[] = $event['actor'];
                $eventBatch[] = $event;

                if (count($eventBatch) >= 5000) {
                    $this->insertEventImportBatches($repoBatch, $actorBatch, $eventBatch);
                    gc_collect_cycles();

                    $repoBatch = [];
                    $actorBatch = [];
                    $eventBatch = [];
                }

                unset($event);
            }

            $this->insertEventImportBatches($repoBatch, $actorBatch, $eventBatch);
            $progressBar->finish();
        }
    }

    /**
     * @param mixed[] $repoBatch
     * @param mixed[] $actorBatch
     * @param mixed[] $eventBatch
     */
    private function insertEventImportBatches(array $repoBatch, array $actorBatch, array $eventBatch): void
    {
        $this->writeRepoRepository->insertList($repoBatch);
        $this->writeActorRepository->insertList($actorBatch);
        $this->writeEventRepository->insertList($eventBatch);
    }
}
