<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Http;

use Overtrue\Keycloak\Serializer\Serializer;
use Psr\Http\Message\ResponseInterface;

class CommandExecutor
{
    public function __construct(
        private readonly Client $client,
        private readonly Serializer $serializer,
    ) {}

    public function executeCommand(Command $command): ResponseInterface
    {
        $options = match ($command->getContentType()) {
            ContentType::JSON => [
                'body' => $this->serializer->serialize($command->getPayload()),
                'headers' => [
                    'Content-Type' => $command->getContentType()->value,
                ],
            ],
            ContentType::FORM_PARAMS => ['form_params' => $command->getPayload()],
        };

        return $this->client->request($command->getMethod()->value, $command->getPath(), $options);
    }
}
