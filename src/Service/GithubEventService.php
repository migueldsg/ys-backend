<?php

namespace App\Service;

use App\Adapter\GithubEventImportAdapter;
use App\Dto\CommandParamDto\ImportGithubEventsCommandParamDto;
use App\Factory\EventFactory;
use App\HttpClient\GHArchiveHttpClient;
use App\Repository\EventRepository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GithubEventService
{
    public function __construct(
        public readonly GHArchiveHttpClient $ghArchiveHttpClient,
        public readonly GithubEventImportAdapter $githubEventImportAdapter,
        public readonly EventRepository $eventRepository
    ) {}

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function importEvents(ImportGithubEventsCommandParamDto $commandParamDto, OutputInterface $output): void
    {
        $progressBar = null;
        for ($i = $commandParamDto->startHour; $i <= $commandParamDto->endHour; $i++) {
            if (true === $progressBar instanceof ProgressBar) {
                $progressBar->finish();
            }

            $zippedEventFileContent = $this->ghArchiveHttpClient->getEventsByDate($commandParamDto->date->format('Y-m-d'), $i);
            $eventDtoList = $this->githubEventImportAdapter->adaptZippedEventFileIntoDto($zippedEventFileContent, $output);

            $progressBar = new ProgressBar($output, count($eventDtoList));
            foreach ($eventDtoList as $eventDto) {
                $this->eventRepository->persist(EventFactory::create($eventDto));
                $progressBar->advance();
            }
        }

        $this->eventRepository->flush();
        $progressBar->finish();
    }
}
