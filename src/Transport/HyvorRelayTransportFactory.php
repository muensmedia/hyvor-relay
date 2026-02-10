<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Muensmedia\HyvorRelay\Transport;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

use function in_array;

/**
 * @author Yann LUCAS
 */
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
