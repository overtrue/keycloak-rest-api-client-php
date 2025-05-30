<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Representation;

use Overtrue\Keycloak\Collection\CredentialCollection;
use Overtrue\Keycloak\Collection\FederatedIdentityCollection;
use Overtrue\Keycloak\Collection\UserConsentCollection;
use Overtrue\Keycloak\Type\ArrayMap;
use Overtrue\Keycloak\Type\BooleanMap;

/**
 * @method BooleanMap getAccess()
 * @method self withAccess(BooleanMap|array|null $access)
 * @method ArrayMap getAttributes()
 * @method self withAttributes(ArrayMap|array|null $attributes)
 * @method UserConsentCollection|null getClientConsents()
 * @method self withClientConsents(?UserConsentCollection $clientConsents)
 * @method ArrayMap getClientRoles()
 * @method self withClientRoles(ArrayMap|array|null $clientRoles)
 * @method int|null getCreatedTimestamp()
 * @method self withCreatedTimestamp(?int $createdTimestamp)
 * @method CredentialCollection|null getCredentials()
 * @method self withCredentials(?CredentialCollection $credentials)
 * @method string[]|null getDisableableCredentialTypes()
 * @method self withDisableableCredentialTypes(?bool $disableableCredentialTypes)
 * @method string|null getEmail()
 * @method self withEmail(?string $email)
 * @method bool|null getEmailVerified()
 * @method self withEmailVerified(?bool $emailVerified)
 * @method bool|null getEnabled()
 * @method self withEnabled(?bool $enabled)
 * @method FederatedIdentityCollection|null getFederatedIdentities()
 * @method self withFederatedIdentities(?FederatedIdentityCollection $federatedIdentites)
 * @method string|null getFederationLink()
 * @method self withFederationLink(?string $federationLink)
 * @method string|null getFirstName()
 * @method self withFirstName(?string $firstName)
 * @method string[]|null getGroups()
 * @method self withGroups(?string[] $groups)
 * @method string|null getId()
 * @method self withId(?string $id)
 * @method string|null getLastName()
 * @method self withLastName(?string $lastName)
 * @method int|null getNotBefore()
 * @method self withNotBefore(?int $notBefore)
 * @method string|null getOrigin()
 * @method self withOrigin(?string $origin)
 * @method string[]|null getRealmRoles()
 * @method self withRealmRoles(?string[] $realmRoles)
 * @method string[]|null getRequiredActions()
 * @method self withRequiredActions(?string[] $requiredActions)
 * @method string|null getSelf()
 * @method self withSelf(?string $self)
 * @method string|null getServiceAccountClientId()
 * @method self withServiceAccountClientId(?string $serviceAccountClientId)
 * @method bool|null getTotp()
 * @method self withTotp(?bool $totp)
 * @method string|null getUsername()
 * @method self withUsername(?string $username)
 *
 * @codeCoverageIgnore
 */
class Member extends Representation
{
    protected BooleanMap $access;

    protected ArrayMap $attributes;

    protected ArrayMap $clientRoles;

    /**
     * @param  BooleanMap|array<string, bool>|null  $access
     * @param  ArrayMap|array<string, string|string[]>|null  $attributes
     * @param  ArrayMap|array<string, string|string[]>|null  $clientRoles
     */
    public function __construct(
        protected ?string $id = null,
        protected ?string $username = null,
        protected ?string $firstName = null,
        protected ?string $lastName = null,
        protected ?string $email = null,
        protected ?bool $emailVerified = null,
        ArrayMap|array|null $attributes = null,
        // todo: userProfileMetadata
        protected ?string $self = null,
        protected ?string $origin = null,
        protected ?int $createdTimestamp = null,
        protected ?bool $enabled = null,
        protected ?bool $totp = null,
        protected ?string $federationLink = null,
        protected ?string $serviceAccountClientId = null,
        protected ?CredentialCollection $credentials = null,
        /** @var string[]|null */
        protected ?array $disableableCredentialTypes = null,
        /** @var string[]|null */
        protected ?array $requiredActions = null,
        protected ?FederatedIdentityCollection $federatedIdentities = null,
        /** @var string[]|null */
        protected ?array $realmRoles = null,
        ArrayMap|array|null $clientRoles = null,
        protected ?UserConsentCollection $clientConsents = null,
        protected ?int $notBefore = null,
        //        ArrayMap|array|null $applicationRoles = null,
        // todo: socialLinks
        /** @var string[]|null */
        protected ?array $groups = null,
        BooleanMap|array|null $access = null,
        // todo: membershipType
    ) {
        $this->access = BooleanMap::make($access);
        $this->attributes = ArrayMap::make($attributes);
        $this->clientRoles = ArrayMap::make($clientRoles);
    }
}
