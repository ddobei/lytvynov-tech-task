<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

use App\Service\WeatherService;


final class WeatherService
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private ContainerBagInterface $params,
    ) {
    }

    public function getWeatherData(string $city): array
    {
        $url = $this->params->get('app.weather_api_url') . "&q={$city}";
        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            if (isset($data['error'])) {
                throw new \Exception($data['error']['message']);
            }

            $result = [
                'city' => $data['location']['name'],
                'country' => $data['location']['country'],
                'temperature' => $data['current']['temp_c'],
                'condition' => $data['current']['condition']['text'],
                'humidity' => $data['current']['humidity'],
                'windSpeed' => $data['current']['wind_kph'],
                'lastUpdated' => $data['current']['last_updated'],
            ];

            $this->logger->info(date('Y-m-d H:i:s') . " - Weather in {$result['city']}: {$result['temperature']}°C, {$result['condition']}\n");
            return $result;

        } catch (\Exception $e) {
            $this->logger->error('Error fetching weather data: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
