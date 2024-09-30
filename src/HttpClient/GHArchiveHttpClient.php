<?php

namespace App\HttpClient;

use App\Dto\ResponseDto\EventResponseDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GHArchiveHttpClient
{
    private const DATE_QUERY_PARAMETER = '{date}-{hour}.json.gz';

    public function __construct(public readonly HttpClientInterface $ghArchiveClient) {}

    /**
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    public function getEventsByDate(string $date, string $hour): string
    {
        $url = str_replace(
            ['{date}','{hour}'],
            [$date, $hour],
            self::DATE_QUERY_PARAMETER
        );

        $response = $this->ghArchiveClient->request('GET', $url);
        if (Response::HTTP_NOT_FOUND === $response->getStatusCode()) {
            throw new \Exception();
        }

        return $response->getContent();
    }
}
