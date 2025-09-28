<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Http;

use Overtrue\Keycloak\Collection\Collection;
use Overtrue\Keycloak\Representation\Representation;

readonly class Command
{
    public function __construct(
        private string $path,
        private Method $method,
        /** @var array<string, mixed> */
        private array $parameters = [],
        /** @var Representation|Collection|array<string, mixed>|null */
        private Representation|Collection|array|string|null $payload = null,
        /** @var Criteria|array<string, string>|null */
        private Criteria|array|null $criteria = null,
        private ContentType $contentType = ContentType::JSON,
    ) {}

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getPath(): string
    {
        $placeholders = array_map(
            static fn (string $parameter): string => '{'.$parameter.'}',
            array_keys($this->parameters),
        );

        $values = array_values($this->parameters);

        $path = str_replace(
            $placeholders,
            $values,
            $this->path,
        );

        return $path.$this->getQuery();
    }

    /**
     * @return Representation|Collection|array<string, mixed>|string|null
     */
    public function getPayload(): Representation|Collection|array|string|null
    {
        return $this->payload;
    }

    public function getQuery(): string
    {
        if (! $this->criteria) {
            return '';
        }

        if ($this->criteria instanceof Criteria) {
            return '?'.http_build_query($this->criteria->jsonSerialize());
        }

        return '?'.http_build_query($this->criteria);
    }

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }
}
