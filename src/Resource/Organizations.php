<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Resource;

use Overtrue\Keycloak\Collection\IdentityProviderCollection;
use Overtrue\Keycloak\Collection\MemberCollection;
use Overtrue\Keycloak\Collection\OrganizationCollection;
use Overtrue\Keycloak\Http\Command;
use Overtrue\Keycloak\Http\ContentType;
use Overtrue\Keycloak\Http\Criteria;
use Overtrue\Keycloak\Http\Method;
use Overtrue\Keycloak\Http\Query;
use Overtrue\Keycloak\Representation\Member;
use Overtrue\Keycloak\Representation\Organization;
use Psr\Http\Message\ResponseInterface;

class Organizations extends Resource
{
    /**
     * @param  \Overtrue\Keycloak\Http\Criteria|array<string, string>|null  $criteria
     */
    public function all(string $realm, Criteria|array|null $criteria = null): OrganizationCollection
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations',
                OrganizationCollection::class,
                ['realm' => $realm],
                $criteria,
            ),
        );
    }

    public function get(string $realm, string $id): Organization
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations/{id}',
                Organization::class,
                ['realm' => $realm, 'id' => $id],
            ),
        );
    }

    /**
     * @param  \Overtrue\Keycloak\Representation\Organization|array<string, mixed>  $organization
     *
     * @throws \Overtrue\Keycloak\Exception\PropertyDoesNotExistException
     * @throws \ReflectionException
     */
    public function create(string $realm, Organization|array $organization): Organization
    {
        if (! $organization instanceof Organization) {
            $organization = Organization::from($organization);
        }

        $response = $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations',
                Method::POST,
                ['realm' => $realm],
                $organization,
            ),
        );

        $id = $this->getIdFromResponse($response);

        if ($id === null) {
            throw new \RuntimeException('Could not extract organization id from response');
        }

        return $this->get($realm, $id);
    }

    /**
     * @param  \Overtrue\Keycloak\Representation\Organization|array<string,mixed>  $organization
     *
     * @throws \Overtrue\Keycloak\Exception\PropertyDoesNotExistException
     * @throws \ReflectionException
     */
    public function update(string $realm, string $id, Organization|array $organization): Organization
    {
        if (! $organization instanceof Organization) {
            $organization = Organization::from($organization);
        }

        $response = $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{id}',
                Method::PUT,
                ['realm' => $realm, 'id' => $id],
                $organization,
            ),
        );

        $id = $this->getIdFromResponse($response);

        if ($id === null) {
            throw new \RuntimeException('Could not extract organization id from response');
        }

        return $this->get($realm, $id);
    }

    public function delete(string $realm, string $id): ResponseInterface
    {
        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{id}',
                Method::DELETE,
                ['realm' => $realm, 'id' => $id],
            ),
        );
    }

    /**
     * @param  \Overtrue\Keycloak\Http\Criteria|array<string, string>|null  $criteria
     */
    public function members(string $realm, string $id, Criteria|array|null $criteria = null): MemberCollection
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations/{orgId}/members',
                MemberCollection::class,
                [
                    'realm' => $realm,
                    'orgId' => $id,
                ],
                $criteria,
            ),
        );
    }

    /**
     * @param  \Overtrue\Keycloak\Http\Criteria|array<string, string>|null  $criteria
     */
    public function membersCount(string $realm, string $id, Criteria|array|null $criteria = null): int
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations/{orgId}/members/count',
                'int',
                [
                    'realm' => $realm,
                    'orgId' => $id,
                ],
                $criteria,
            ),
        );
    }

    public function member(string $realm, string $id, string $memberId): Member
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations/{orgId}/members/{memberId}',
                Member::class,
                [
                    'realm' => $realm,
                    'orgId' => $id,
                    'memberId' => $memberId,
                ],
            ),
        );
    }

    public function addMember(string $realm, string $id, string $userId): ResponseInterface
    {
        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{orgId}/members',
                Method::POST,
                [
                    'realm' => $realm,
                    'orgId' => $id,
                ],
                payload: $userId,
                contentType: ContentType::JSON,
            ),
        );
    }

    public function deleteMember(string $realm, string $id, string $memberId): ResponseInterface
    {
        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{orgId}/members/{memberId}',
                Method::DELETE,
                [
                    'realm' => $realm,
                    'orgId' => $id,
                    'memberId' => $memberId,
                ],
            ),
        );
    }

    public function memberOrganizations(string $realm, string $memberId): OrganizationCollection
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations/members/{memberId}/organizations',
                OrganizationCollection::class,
                [
                    'realm' => $realm,
                    'memberId' => $memberId,
                ],
            ),
        );
    }

    public function orgMemberOrganizations(string $realm, string $id, string $memberId): OrganizationCollection
    {
        return $this->queryExecutor->executeQuery(
            new Query(
                '/admin/realms/{realm}/organizations/{orgId}/members/{memberId}/organizations',
                OrganizationCollection::class,
                [
                    'realm' => $realm,
                    'orgId' => $id,
                    'memberId' => $memberId,
                ],
            ),
        );
    }

    public function inviteUser(string $realm, string $id, string $email, string $firstName, string $lastName): ResponseInterface
    {
        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{id}/members/invite-user',
                Method::POST,
                ['realm' => $realm, 'id' => $id],
                payload: [
                    'email' => $email,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                ],
                contentType: ContentType::FORM_PARAMS,
            ),
        );
    }

    public function inviteExistingUser(string $realm, string $id, string $userId): ResponseInterface
    {
        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{id}/members/invite-existing-user',
                Method::POST,
                ['realm' => $realm, 'id' => $id],
                payload: [
                    'userId' => $userId,
                ],
                contentType: ContentType::FORM_PARAMS,
            ),
        );
    }

    /**
     * @param array<int, \Overtrue\Keycloak\Representation\IdentityProvider> $identityProviders
     * @throws \ReflectionException
     * @throws \Overtrue\Keycloak\Exception\PropertyDoesNotExistException
     */
    public function linkIdp(string $realm, string $id, IdentityProviderCollection|array $identityProviders): ResponseInterface
    {
        if (! $identityProviders instanceof IdentityProviderCollection) {
            $identityProviders = new IdentityProviderCollection($identityProviders);
        }

        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{id}/identity-providers',
                Method::POST,
                ['realm' => $realm, 'id' => $id],
                payload: $identityProviders,
                contentType: ContentType::FORM_PARAMS,
            ),
        );
    }

    public function unlinkIdp(string $realm, string $id, string $alias): ResponseInterface
    {
        return $this->commandExecutor->executeCommand(
            new Command(
                '/admin/realms/{realm}/organizations/{id}/identity-providers/{alias}',
                Method::DELETE,
                ['realm' => $realm, 'id' => $id, 'alias' => $alias],
            ),
        );
    }

    public function getIdFromResponse(ResponseInterface $response): ?string
    {
        // Location: http://keycloak:8080/admin/realms/master/organizations/b1651206-a558-453f-afc6-e329f9bacb8c
        $location = $response->getHeaderLine('Location');

        preg_match('~/organizations/(?<id>[a-z0-9\-]+)$~', $location, $matches);

        return $matches['id'] ?? null;
    }
}
