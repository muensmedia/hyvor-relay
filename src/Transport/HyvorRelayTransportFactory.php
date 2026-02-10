<?php

/*
 * Derived from symfony/sendinblue-mailer (MIT).
 * See THIRD_PARTY_NOTICES.md for the applicable copyright and license text.
 */

namespace Muensmedia\HyvorRelay\Transport;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

use function in_array;

final class HyvorRelayTransportFactory extends AbstractTransportFactory
{
    /**
     * @throws \Throwable
     */
    public function create(Dsn $dsn): TransportInterface
    {
        throw_if(
            ! in_array($dsn->getScheme(), $this->getSupportedSchemes(), true),
            UnsupportedSchemeException::class,
            $dsn,
            'hyvor-relay',
            $this->getSupportedSchemes()
        );

        return new HyvorRelayApiTransport($this->getUser($dsn), $this->client, $this->dispatcher, $this->logger)
            ->setHost($dsn->getHost() === 'default' ? null : $dsn->getHost())
            ->setPort($dsn->getPort());
    }

    protected function getSupportedSchemes(): array
    {
        return ['hyvor+api'];
    }
}
