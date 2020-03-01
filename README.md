# Akeneo PIM Events API

The Events API is a smooth and easy way to build integrations that respond to activities in Akeneo PIM. 
All you need is PIM Events API Bundle and an endpoint where to send Akeneo PIM events.

## Table of contents
* [Getting Started](#Getting-Started)
* [Functionality](#Functionality)
* [License](#License)

## Getting Started

### Requirements

* Akeneo PIM >= 4.0 (CE & EE)

### Installation

Install via composer:

```bash
php composer.phar require trilix/akeneo-events-api-bundle:^0.6.0
```

To enable the bundle add to the *config/bundles.php* file:

```php
return [
    // ...
    Trilix\EventsApiBundle\TrilixEventsApiBundle::class => ['all' => true]
]
```

Add the following line at the end of env file:

```yaml
EVENTS_API_REQUEST_URL=your_request_url
```

where `your_request_url` is a target location where all the events (see [event types](#Event-types-delivered-over-Events-API)) will be delivered.

Create file *config/packages/trilix_events_api.yml* with the following:

```yaml
trilix_events_api:
    transport:
        factory: "pim_events_api.transport_factory.http"
        options:
            request_url: "%env(EVENTS_API_REQUEST_URL)%"
```

Clear cache:

```bash
php bin/console cache:clear --env=prod
```

Run the following command to create a job to deliver events to consumer:

```bash
php bin/console akeneo:batch:create-job 'Deliver outer event to consumer' deliver_outer_event_to_consumer internal deliver_outer_event_to_consumer
```

Make sure Akeneo job queue daemon is running. For more information read [Setting up the job queue daemon](https://docs.akeneo.com/latest/install_pim/manual/daemon_queue.html#setting-up-the-job-queue-daemon).

## Functionality

### How it works

Some event(s) happens in Akeneo PIM. This triggers a mechanism to send those event(s) as HTTP POST request to your Request URL.
Each request contains event, with correspondent [event type](#Event-types-delivered-over-Events-API) presented in JSON format (see [example](#Example-of-*category_updated*-event)).

Events API sends one request per one event, and sending of requests happens in real-time.

### Event types delivered over Events API

| **Event** | **Description** |
| --------------------- |:----------------------------------:|
| category_created      | New category was created           |
| category_updated      | Existing category was updated      |
| category_removed      | Existing category was deleted      |
| attribute_created     | New attribute was created          |
| attribute_updated     | Existing attribute was updated     |
| attribute_removed     | Existing attribute was deleted     |
| family_created        | New family was created             |
| family_updated        | Existing family was updated        |
| family_removed        | Existing family was deleted        |
| product_created       | New product was created            |
| product_updated       | Existing product was updated       |
| product_removed       | Existing product was deleted       |
| product_model_created | New product model was created      |
| product_model_updated | Existing product model was updated |
| product_model_removed | Existing product model was deleted |

### Example of *category_updated* event

```json
{
  "event_type": "category_updated",
  "payload": {
    "code": "cameras",
    "labels": {
      "de_DE": "Cameras",
      "en_US": "Cameras new name",
      "fr_FR": "Caméras"
    },
    "parent": "master"
  },
  "event_time": 1565021907
}
```
### Example of *product_model_removed* event
```json
{
  "event_type": "product_model_removed",
  "payload": {
    "code": "derby"
  },
  "event_time": 1579792377
}
```

### Event Type Structure

| Field        | Type | Description                                                                                 |
| ------------ |:-------:|:----------------------------------------------------------------------------------------:|
| *event_type* | String  | Type of event which happened (see [event types](#Event-types-delivered-over-Events-API)) |
| *payload*    | Object  | Contains information which represents the event. For events related to deletion of entity it contains entity only identifier (identifier value for Products and code for all others) |
| *event_time* | Integer | Timestamp in seconds when the event was created                                          |

### Attention :heavy_exclamation_mark:

If Akeneo family contains variants, then during family update (or it's variants as well),
Akeneo will re-save related products. It will trigger sending *product_updated* events.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
