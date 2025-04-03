<?php

namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use App\Service\WeatherService;

final class WeatherServiceTest extends TestCase
{
    private $mockHttpClient;
    private $mockLogger;
    private $mockContainerBag;
    private $mockResponse;
    private $weatherService;

    public function setUp(): void
    {
        $this->mockHttpClient = $this->createMock(HttpClientInterface::class);
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockContainerBag = $this->createMock(ContainerBagInterface::class);
        $this->mockResponse = $this->createMock(ResponseInterface::class);

        $this->weatherService = new WeatherService(
            $this->mockHttpClient,
            $this->mockLogger,
            $this->mockContainerBag
        );

        $this->mockHttpClient
            ->method('request')
            ->willReturn($this->mockResponse);

        $this->mockContainerBag
            ->method('get')
            ->willReturn('someApiKey');
    }

    public function testGetWeatherDataSuccess(): void
    {
        $this->mockResponse
            ->method('toArray')
            ->willReturn([
                'location' => [
                    'name' => 'London',
                    'country' => 'United Kingdom'
                ],
                'current' => [
                    'temp_c' => 11.2,
                    'condition' => [
                        'text' => 'Sunny'
                    ],
                    'humidity' => 66,
                    'wind_kph' => 17.6,
                    'last_updated' => '2025-04-03 08:45'
                ]
            ]);

        $this->mockLogger
            ->expects($this->once())
            ->method('info');

        $data = $this->weatherService->getWeatherData('London');

        $this->assertArrayHasKey('city', $data);
        $this->assertEquals('London', $data['city']);
        $this->assertArrayHasKey('temperature', $data);
        $this->assertEquals(11.2, $data['temperature']);
    }

    public function testGetWeatherDataError(): void
    {
        $this->mockResponse
            ->method('toArray')
            ->willReturn([
                'error' => [
                    'message' => 'some error'
                ]
            ]);

        $this->mockLogger
            ->expects($this->once())
            ->method('error');

        $data = $this->weatherService->getWeatherData('London');

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('some error', $data['error']);
    }
}
