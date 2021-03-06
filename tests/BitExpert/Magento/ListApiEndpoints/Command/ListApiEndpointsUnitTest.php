<?php

/*
 * This file is part of the magerun2-list-api-endpoints package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BitExpert\Magento\ListApiEndpoints\Command;

use InvalidArgumentException;
use N98\Magento\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Unit tests for {@link \BitExpert\Magento\ListApiEndpoints\Command\ListApiEndpoints}.
 */
class ListApiEndpointsUnitTest extends TestCase
{
    /**
     * Number of lines rendered for a table without any data, just the table header
     */
    const EMPTY_TABLE_OUTPUT_LINES = 3;
    /**
     * Minimum number of lines rendered for a table with data and the table header
     */
    const TABLE_OUTPUT_LINES = 4;
    /**
     * @var InputInterface
     */
    private $input;
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var Application
     */
    private $application;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $outputFormatter = $this->createMock(OutputFormatterInterface::class);
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->output->expects($this->any())
            ->method('getFormatter')
            ->willReturn($outputFormatter);
        $this->application = new Application();
        $this->application->init([], $this->input, $this->output);
    }

    /**
     * @test
     */
    public function missingOutputFormatParameterThrowsException()
    {
        self::expectException(InvalidArgumentException::class);

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn([]);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * @test
     */
    public function withOutputFormatParameterSetTheCommandWillRenderTableStructure()
    {
        // since no services are returned, just the table header is rendered
        $this->output->expects($this->exactly(self::EMPTY_TABLE_OUTPUT_LINES))
            ->method('writeln');

        $this->input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap([
                [ListApiEndpoints::OPTION_FILTER_ROUTE, ''],
                [ListApiEndpoints::OPTION_FILTER_METHOD, ''],
                [ListApiEndpoints::OPTION_OUTPUT_FORMAT, 'table'],
            ]));

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn([]);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * @test
     */
    public function forEachDefinedRouteTheCommandWillRenderTableRow()
    {
        $services['routes'] = [
            '/route' => [
                'GET' => ['resources' => '{}']
            ]
        ];

        $this->output->expects($this->exactly(self::TABLE_OUTPUT_LINES + 1))
            ->method('writeln');

        $this->input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap([
                [ListApiEndpoints::OPTION_FILTER_ROUTE, ''],
                [ListApiEndpoints::OPTION_FILTER_METHOD, ''],
                [ListApiEndpoints::OPTION_OUTPUT_FORMAT, 'table'],
            ]));

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn($services);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * @test
     */
    public function forEachFilteredRouteByMethodTheCommandWillRenderTableRow()
    {
        $filter = 'GET';
        $services['routes'] = [
            '/route' => [
                'GET' => ['resources' => '{}'],
                'PUT' => ['resources' => '{}']
            ],
            '/other-route' => [
                'GET' => ['resources' => '{}'],
                'POST' => ['resources' => '{}'],
                'DELETE' => ['resources' => '{}']
            ],
        ];

        $this->output->expects($this->exactly(self::TABLE_OUTPUT_LINES + 2))
            ->method('writeln');

        $this->input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap([
                [ListApiEndpoints::OPTION_FILTER_ROUTE, ''],
                [ListApiEndpoints::OPTION_FILTER_METHOD, $filter],
                [ListApiEndpoints::OPTION_OUTPUT_FORMAT, 'table'],
            ]));

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn($services);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * @test
     */
    public function forMultipleFilteredRoutesByMethodTheCommandWillRenderTableRow()
    {
        $filter = 'GET, POST';
        $services['routes'] = [
            '/route' => [
                'GET' => ['resources' => '{}'],
                'PUT' => ['resources' => '{}']
            ],
            '/other-route' => [
                'GET' => ['resources' => '{}'],
                'POST' => ['resources' => '{}'],
                'DELETE' => ['resources' => '{}']
            ],
        ];

        $this->output->expects($this->exactly(self::TABLE_OUTPUT_LINES + 3))
            ->method('writeln');

        $this->input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap([
                [ListApiEndpoints::OPTION_FILTER_ROUTE, ''],
                [ListApiEndpoints::OPTION_FILTER_METHOD, $filter],
                [ListApiEndpoints::OPTION_OUTPUT_FORMAT, 'table'],
            ]));

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn($services);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * @test
     */
    public function forMultipleFilteredRoutesByRouteTheCommandWillRenderTableRow()
    {
        $filter = '';
        $route = 'other';
        $services['routes'] = [
            '/route' => [
                'GET' => ['resources' => '{}'],
                'PUT' => ['resources' => '{}']
            ],
            '/other-route' => [
                'GET' => ['resources' => '{}'],
                'POST' => ['resources' => '{}'],
                'DELETE' => ['resources' => '{}']
            ],
        ];

        $this->output->expects($this->exactly(self::TABLE_OUTPUT_LINES + 3))
            ->method('writeln');

        $this->input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap([
                [ListApiEndpoints::OPTION_FILTER_ROUTE, $route],
                [ListApiEndpoints::OPTION_FILTER_METHOD, $filter],
                [ListApiEndpoints::OPTION_OUTPUT_FORMAT, 'table'],
            ]));

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn($services);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * @test
     */
    public function forMultipleFilteredRoutesByMethodAndRouteTheCommandWillRenderTableRow()
    {
        $filter = 'GET, POST';
        $route = 'other';
        $services['routes'] = [
            '/route' => [
                'GET' => ['resources' => '{}'],
                'PUT' => ['resources' => '{}']
            ],
            '/other-route' => [
                'GET' => ['resources' => '{}'],
                'POST' => ['resources' => '{}'],
                'DELETE' => ['resources' => '{}']
            ],
        ];

        $this->output->expects($this->exactly(self::TABLE_OUTPUT_LINES + 2))
            ->method('writeln');

        $this->input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap([
                [ListApiEndpoints::OPTION_FILTER_ROUTE, $route],
                [ListApiEndpoints::OPTION_FILTER_METHOD, $filter],
                [ListApiEndpoints::OPTION_OUTPUT_FORMAT, 'table'],
            ]));

        /** @var ListApiEndpoints $command */
        $command = $this->getApiEndpointsMock();
        $command->method('getDefinedServices')
            ->willReturn($services);
        $command->setApplication($this->application);
        $command->run($this->input, $this->output);
    }

    /**
     * Helper method to configure a mocked version of {@link \BitExpert\Magento\LisaApiEndpoints\Command\ListApiEndpoints}.
     *
     * @return ListApiEndpoints|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getApiEndpointsMock()
    {
        $command = $this->getMockBuilder(ListApiEndpoints::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getDefinedServices', 'detectMagento', 'initMagento'])
            ->getMock();
        $command->method('detectMagento')
            ->willReturn(null);
        $command->method('initMagento')
            ->willReturn(true);
        return $command;
    }
}
