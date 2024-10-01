<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\CommandParamDto\ImportGithubEventsCommandParamDto;
use App\Service\GithubEventService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(name: 'app:import-github-events')]
class ImportGitHubEventsCommand extends Command
{
    public function __construct(
        public readonly DenormalizerInterface $denormalizer,
        public readonly ValidatorInterface $validator,
        public readonly GithubEventService $githubEventService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import Github events')
            ->addArgument('date', InputArgument::REQUIRED, 'Date of the events.')
            ->addArgument('startHour', InputArgument::OPTIONAL, 'Starting hour of the events.', 0)
            ->addArgument('endHour', InputArgument::OPTIONAL, 'Ending hour of the events.', 23);
    }

    /**
     * @throws ExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $commandParamDto = $this->denormalizer->denormalize(
                $input->getArguments(),
                ImportGithubEventsCommandParamDto::class
            );

            $violationList = $this->validator->validate($commandParamDto);
            if (0 < count($violationList)) {
                return Command::FAILURE;
            }

            $this->githubEventService->importEvents($commandParamDto, $io);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success('Events imported successfully.');
        return Command::SUCCESS;
    }
}
