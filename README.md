# Akeneo PIM Events API

## Installation

Install via composer:

```bash
php composer.phar require trilix/akeneo-events-api-bundle:^0.4.0-dev
```

Then enable the bundle in the app/AppKernel.php file in the registerProjectBundles() method:

```php
$bundles = [
    // ...
    new \Trilix\EventsApiBundle\TrilixEventsApiBundle(),
]
```

Add the following line at the end of app/config/parameters.yml:

```yaml
default_events_api_app_uri: 'consumer'
```

where `consumer` is 3rd party http endpoint (or service) which accepts events from Akeneo.

Add the following lines at the end of app/config/config.yml:

```yaml
trilix_events_api:
    applications:
        default:
            uri: "%default_events_api_app_uri%"
```

Run the following command to create job to deliver events to consumer:

```bash
bin/console akeneo:batch:create-job 'Deliver outer event to consumer' deliver_outer_event_to_consumer internal deliver_outer_event_to_consumer
```
