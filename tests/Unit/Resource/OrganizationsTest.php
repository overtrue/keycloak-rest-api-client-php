<?php

declare(strict_types=1);

namespace Overtrue\Keycloak\Test\Unit\Resource;

use GuzzleHttp\Psr7\Response;
use Overtrue\Keycloak\Collection\MemberCollection;
use Overtrue\Keycloak\Collection\OrganizationCollection;
use Overtrue\Keycloak\Http\Command;
use Overtrue\Keycloak\Http\CommandExecutor;
use Overtrue\Keycloak\Http\ContentType;
use Overtrue\Keycloak\Http\Method;
use Overtrue\Keycloak\Http\Query;
use Overtrue\Keycloak\Http\QueryExecutor;
use Overtrue\Keycloak\Representation\Member;
use Overtrue\Keycloak\Representation\Organization;
use Overtrue\Keycloak\Resource\Organizations;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Organizations::class)]
class OrganizationsTest extends TestCase
{
    public function test_get_all_organizations(): void
    {
        $query = new Query(
            '/admin/realms/{realm}/organizations',
            OrganizationCollection::class,
            [
                'realm' => 'test-realm',
            ],
        );

        $organizationCollection = new OrganizationCollection([
            new Organization(id: 'test-organization-1'),
            new Organization(id: 'test-organization-2'),
        ]);

        $queryExecutor = $this->createMock(QueryExecutor::class);
        $queryExecutor->expects(static::once())
            ->method('executeQuery')
            ->with($query)
            ->willReturn($organizationCollection);

        $organizations = new Organizations(
            $this->createMock(CommandExecutor::class),
            $queryExecutor,
        );

        static::assertSame(
            $organizationCollection,
            $organizations->all('test-realm'),
        );
    }

    public function test_get_organization(): void
    {
        $query = new Query(
            '/admin/realms/{realm}/organizations/{id}',
            Organization::class,
            [
                'realm' => 'test-realm',
                'id' => 'test-organization-1',
            ],
        );

        $organization = new Organization(id: 'test-organization-1');

        $queryExecutor = $this->createMock(QueryExecutor::class);
        $queryExecutor->expects(static::once())
            ->method('executeQuery')
            ->with($query)
            ->willReturn($organization);

        $organizations = new Organizations(
            $this->createMock(CommandExecutor::class),
            $queryExecutor,
        );

        static::assertSame(
            $organization,
            $organizations->get('test-realm', 'test-organization-1'),
        );
    }

    public function test_create_organization(): void
    {
        $createdOrganization = new Organization(id: 'uuid', name: 'test-organization-1');

        $command = new Command(
            '/admin/realms/{realm}/organizations',
            Method::POST,
            [
                'realm' => 'test-realm',
            ],
            $createdOrganization,
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204, [
                'Location' => '/admin/realms/test-realm/organizations/uuid',
            ]));

        $organizations = $this->getMockBuilder(Organizations::class)
            ->setConstructorArgs([$commandExecutor, $this->createMock(QueryExecutor::class)])
            ->onlyMethods(['get'])
            ->getMock();
        $organizations->expects(static::once())
            ->method('get')
            ->with('test-realm', 'uuid')
            ->willReturn($createdOrganization);

        $organization = $organizations->create('test-realm', $createdOrganization);

        static::assertSame(
            $createdOrganization->getId(),
            $organization->getId(),
        );
    }

    public function test_delete_organization(): void
    {
        $command = new Command(
            '/admin/realms/{realm}/organizations/{id}',
            Method::DELETE,
            [
                'realm' => 'test-realm',
                'id' => 'uuid',
            ],
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204));

        $organizations = new Organizations(
            $commandExecutor,
            $this->createMock(QueryExecutor::class),
        );

        $response = $organizations->delete('test-realm', 'uuid');

        static::assertSame(204, $response->getStatusCode());
    }

    public function test_list_members(): void
    {
        $query = new Query(
            '/admin/realms/{realm}/organizations/{orgId}/members',
            MemberCollection::class,
            [
                'realm' => 'test-realm',
                'orgId' => 'uuid',
            ],
        );

        $memberCollection = new MemberCollection([
            new Member(id: 'test-member-1'),
            new Member(id: 'test-member-2'),
        ]);

        $queryExecutor = $this->createMock(QueryExecutor::class);
        $queryExecutor->expects(static::once())
            ->method('executeQuery')
            ->with($query)
            ->willReturn($memberCollection);

        $organizations = new Organizations(
            $this->createMock(CommandExecutor::class),
            $queryExecutor,
        );

        static::assertSame(
            $memberCollection,
            $organizations->members('test-realm', 'uuid'),
        );
    }

    public function test_members_count()
    {
        $query = new Query(
            '/admin/realms/{realm}/organizations/{orgId}/members/count',
            'int',
            [
                'realm' => 'test-realm',
                'orgId' => 'uuid',
            ],
        );

        $queryExecutor = $this->createMock(QueryExecutor::class);
        $queryExecutor->expects(static::once())
            ->method('executeQuery')
            ->with($query)
            ->willReturn(2);

        $organizations = new Organizations(
            $this->createMock(CommandExecutor::class),
            $queryExecutor,
        );

        static::assertSame(
            2,
            $organizations->membersCount('test-realm', 'uuid'),
        );
    }

    public function test_get_member()
    {
        $query = new Query(
            '/admin/realms/{realm}/organizations/{orgId}/members/{memberId}',
            Member::class,
            [
                'realm' => 'test-realm',
                'orgId' => 'uuid',
                'memberId' => 'test-member-1',
            ],
        );

        $member = new Member(id: 'test-member-1');

        $queryExecutor = $this->createMock(QueryExecutor::class);
        $queryExecutor->expects(static::once())
            ->method('executeQuery')
            ->with($query)
            ->willReturn($member);

        $organizations = new Organizations(
            $this->createMock(CommandExecutor::class),
            $queryExecutor,
        );

        static::assertSame(
            $member,
            $organizations->member('test-realm', 'uuid', 'test-member-1'),
        );
    }

    public function test_create_members()
    {
        $command = new Command(
            '/admin/realms/{realm}/organizations/{orgId}/members',
            Method::POST,
            [
                'realm' => 'test-realm',
                'orgId' => 'uuid',
            ],
            'test-user-id',
            contentType: ContentType::JSON,
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204));

        $organizations = new Organizations(
            $commandExecutor,
            $this->createMock(QueryExecutor::class),
        );

        $response = $organizations->addMember('test-realm', 'uuid', 'test-user-id');

        static::assertSame(204, $response->getStatusCode());
    }

    public function test_delete_member()
    {
        $command = new Command(
            '/admin/realms/{realm}/organizations/{orgId}/members/{memberId}',
            Method::DELETE,
            [
                'realm' => 'test-realm',
                'orgId' => 'uuid',
                'memberId' => 'test-member-1',
            ],
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204));

        $organizations = new Organizations(
            $commandExecutor,
            $this->createMock(QueryExecutor::class),
        );

        $response = $organizations->deleteMember('test-realm', 'uuid', 'test-member-1');

        static::assertSame(204, $response->getStatusCode());
    }

    public function test_list_org_member_organizations()
    {
        $query = new Query(
            '/admin/realms/{realm}/organizations/{orgId}/members/{memberId}/organizations',
            OrganizationCollection::class,
            [
                'realm' => 'test-realm',
                'orgId' => 'uuid',
                'memberId' => 'test-member-1',
            ],
        );

        $organizationCollection = new OrganizationCollection([
            new Organization(id: 'test-organization-1'),
            new Organization(id: 'test-organization-2'),
        ]);

        $queryExecutor = $this->createMock(QueryExecutor::class);
        $queryExecutor->expects(static::once())
            ->method('executeQuery')
            ->with($query)
            ->willReturn($organizationCollection);

        $organizations = new Organizations(
            $this->createMock(CommandExecutor::class),
            $queryExecutor,
        );

        static::assertSame(
            $organizationCollection,
            $organizations->orgMemberOrganizations('test-realm', 'uuid', 'test-member-1'),
        );
    }

    public function test_invite_user(): void
    {
        $command = new Command(
            '/admin/realms/{realm}/organizations/{id}/members/invite-user',
            Method::POST,
            [
                'realm' => 'test-realm',
                'id' => 'uuid',
            ],
            [
                'email' => 'email',
                'firstName' => 'first name',
                'lastName' => 'last name',
            ],
            contentType: ContentType::FORM_PARAMS,
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204));

        $organizations = new Organizations(
            $commandExecutor,
            $this->createMock(QueryExecutor::class),
        );

        $response = $organizations->inviteUser('test-realm', 'uuid', 'email', 'first name', 'last name');

        static::assertSame(204, $response->getStatusCode());
    }

    public function test_invite_existing_user()
    {
        $command = new Command(
            '/admin/realms/{realm}/organizations/{id}/members/invite-existing-user',
            Method::POST,
            [
                'realm' => 'test-realm',
                'id' => 'uuid',
            ],
            [
                'userId' => 'test-user-id',
            ],
            contentType: ContentType::FORM_PARAMS,
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204));

        $organizations = new Organizations(
            $commandExecutor,
            $this->createMock(QueryExecutor::class),
        );

        $response = $organizations->inviteExistingUser('test-realm', 'uuid', 'test-user-id');

        static::assertSame(204, $response->getStatusCode());
    }

    public function test_create_organization_without_response_header_location(): void
    {
        $createdOrganization = new Organization(id: 'uuid', name: 'test-organization-1');

        $command = new Command(
            '/admin/realms/{realm}/organizations',
            Method::POST,
            [
                'realm' => 'test-realm',
            ],
            $createdOrganization,
        );

        $commandExecutor = $this->createMock(CommandExecutor::class);
        $commandExecutor->expects(static::once())
            ->method('executeCommand')
            ->with($command)
            ->willReturn(new Response(204, [
                // 'Location' => '/admin/realms/test-realm/organizations/uuid',
            ]));

        $organizations = new Organizations(
            $commandExecutor,
            $this->createMock(QueryExecutor::class),
        );

        self::expectExceptionMessage('Could not extract organization id from response');

        $organizations->create('test-realm', $createdOrganization);

        self::fail('Expected exception not thrown');
    }
}
