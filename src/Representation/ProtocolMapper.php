<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Representation;

use Overtrue\Keycloak\Type\StringMap;

/**
 * @method StringMap getConfig()
 * @method self withConfig(StringMap|array|null $config)
 * @method bool|null getConsentRequired()
 * @method self withConsentRequired(?bool $consentRequired)
 * @method string getId()
 * @method self withId(?string $id)
 * @method string getName()
 * @method self withName(?string $name)
 * @method string getProtocol()
 * @method self withProtocol(?string $protocol)
 * @method string getProtocolMapper()
 * @method self withProtocolMapper(?string $protocolMapper)
 *
 * @codeCoverageIgnore
 */
class ProtocolMapper extends Representation
{
    protected StringMap $config;

    /**
     * @param  \Overtrue\Keycloak\Type\StringMap|array<string, string>|null  $config
     */
    public function __construct(
        StringMap|array|null $config = null,
        protected ?bool $consentRequired = null,
        protected ?string $id = null,
        protected ?string $name = null,
        protected ?string $protocol = null,
        protected ?string $protocolMapper = null,
    ) {
        $this->config = StringMap::make($config);
    }
}
