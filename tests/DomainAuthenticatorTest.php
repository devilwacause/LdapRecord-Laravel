<?php

namespace LdapRecord\Laravel\Tests;

use Mockery as m;
use LdapRecord\Models\Entry;
use LdapRecord\Laravel\Domain;
use LdapRecord\ConnectionInterface;
use LdapRecord\Laravel\DomainAuthenticator;
use LdapRecord\Laravel\Events\Authenticated;
use LdapRecord\Laravel\Events\Authenticating;
use LdapRecord\Laravel\Events\AuthenticationFailed;

class DomainAuthenticatorTest extends TestCase
{
    public function test_attempt_passes()
    {
        $dn = 'cn=John Doe,dc=local,dc=com';

        $model = new Entry();
        $model->setDn($dn);

        $connection = m::mock(ConnectionInterface::class);
        $connection->shouldReceive('auth')->once()->andReturnSelf();
        $connection->shouldReceive('attempt')->once()->withArgs([$dn, 'password'])->andReturnTrue();

        $domain = new Domain('test');
        $domain->setConnection($connection);

        $auth = new DomainAuthenticator($domain);

        $this->expectsEvents([
            Authenticating::class,
            Authenticated::class,
        ]);

        $this->doesntExpectEvents([AuthenticationFailed::class]);

        $this->assertTrue($auth->attempt($model, 'password'));
    }

    public function test_attempt_failed()
    {
        $dn = 'cn=John Doe,dc=local,dc=com';

        $model = new Entry();
        $model->setDn($dn);

        $connection = m::mock(ConnectionInterface::class);
        $connection->shouldReceive('auth')->once()->andReturnSelf();
        $connection->shouldReceive('attempt')->once()->withArgs([$dn, 'password'])->andReturnFalse();

        $domain = new Domain('test');
        $domain->setConnection($connection);

        $auth = new DomainAuthenticator($domain);

        $this->expectsEvents([
            Authenticating::class,
            AuthenticationFailed::class,
        ]);

        $this->doesntExpectEvents([Authenticated::class]);

        $this->assertFalse($auth->attempt($model, 'password'));
    }
}