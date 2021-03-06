<?php
declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2018 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Application\Stage;

use phpDocumentor\Configuration\CommandlineOptionsMiddleware;
use phpDocumentor\Configuration\Configuration;
use phpDocumentor\Configuration\ConfigurationFactory;
use phpDocumentor\DomainModel\Uri;

final class Configure
{
    /** @var ConfigurationFactory */
    private $configFactory;

    /** @var Configuration */
    private $configuration;

    /**
     * Configure constructor.
     */
    public function __construct(ConfigurationFactory $configFactory, Configuration $configuration)
    {
        $this->configFactory = $configFactory;
        $this->configuration = $configuration;
    }

    /**
     * @return string[]
     */
    public function __invoke(array $options): array
    {
        $this->configFactory->addMiddleware(
            new CommandlineOptionsMiddleware($options)
        );

        if ($options['config'] ?? null) {
            $this->configuration->exchangeArray(
                $this->configFactory->fromUri(new Uri(realpath($options['config'])))->getArrayCopy()
            );
        } else {
            $this->configuration->exchangeArray(
                $this->configFactory->fromDefaultLocations()->getArrayCopy()
            );
        }

        return $this->configuration->getArrayCopy();
    }
}
