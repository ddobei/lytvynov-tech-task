# WeatherApp

## Description

This project is a Symfony application for retrieving weather data from an API. It implements a service for interacting with the weather API, a controller for providing data via an endpoint, a Twig template for displaying the data on a web page, and testing the service.

## Requirements

1. To start the project you must have following tools installed on your machine:

   - git
   - Docker
   - Docker Compose

2. Port 8080 must be free on your host machine

3. Register and get API key from [https://www.weatherapi.com/](https://www.weatherapi.com/)

## Configuration

1. Clone project:

   ```bash
   git clone https://github.com/ddobei/lytvynov-tech-task.git
   cd lytvynov-tech-task

   ```

2. Copy .env file to .env.local inside app folder

   ```bash
   cp app/.env  app/.env.local

   ```

3. Open .env.local with your favourite text editor, find WEATHER_API_KEY= and fill in API key you received from www.weatherapi.com, save changes

4. Start project:

   ```bash
   docker compose up -d

   ```

5. Install dependencies:

   ```bash
   docker compose run --rm php82-service bash -c "composer install"

   ```

6. Open [http://localhost:8080](http://localhost:8080) with your browser
