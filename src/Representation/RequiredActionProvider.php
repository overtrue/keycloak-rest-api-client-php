<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Representation;

use Overtrue\Keycloak\Type\StringMap;

/**
 * @method string|null getAlias()
 * @method self withAlias(?string $alias)
 * @method StringMap getConfig()
 * @method self withConfig(StringMap|array|null $config)
 * @method bool|null getDefaultAction()
 * @method self withDefaultAction(?bool $defaultAction)
 * @method bool|null getEnabled()
 * @method self withEnabled(?bool $enabled)
 * @method string|null getName()
 * @method self withName(?string $name)
 * @method bool|null getPriority()
 * @method self withPriority(?bool $priority)
 * @method string|null getProviderId()
 * @method self withProviderId(?string $providerId)
 *
 * @codeCoverageIgnore
 */
class RequiredActionProvider extends Representation
{
    protected StringMap $config;

    /**
     * @param  \Overtrue\Keycloak\Type\StringMap|array<string, mixed>|null  $config
     */
    public function __construct(
        protected ?string $alias = null,
        StringMap|array|null $config = null,
        protected ?bool $defaultAction = null,
        protected ?bool $enabled = null,
        protected ?string $name = null,
        protected ?bool $priority = null,
        protected ?string $providerId = null,
    ) {
        $this->config = StringMap::make($config);
    }
}
