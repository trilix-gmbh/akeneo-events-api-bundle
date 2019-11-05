# Akeneo PIM Events API

The Events API is a smooth and easy way to build integrations that respond to activities in Akeneo PIM. 
All you need is PIM Events API Bundle and an endpoint where to send Akeneo PIM events.

## Table of contents
* [Getting Started](#Getting-Started)
* [Functionality](#Functionality)
* [License](#License)

## Getting Started

### Requirements

* Akeneo PIM >= 3.0 (CE & EE)

### Installation

Install via composer:

```bash
php composer.phar require trilix/akeneo-events-api-bundle:^0.5.0
```

To enable the bundle add to the *app/AppKernel.php* file in the registerProjectBundles() method:

```php
$bundles = [
    // ...
    new \Trilix\EventsApiBundle\TrilixEventsApiBundle(),
]
```

Add the following line at the end of *app/config/parameters.yml*:

```yaml
events_api_request_url: 'endpoint_url'
```

where `endpoint_url` is an endpoint url of a consumer which accepts events from Akeneo PIM.

Add the following lines at the end of *app/config/config.yml*:

```yaml
trilix_events_api:
    transport:
        factory: "pim_events_api.transport_factory.http"
        options:
            request_url: "%events_api_request_url%"
```

Run the following command to create a job to deliver events to consumer:

```bash
bin/console akeneo:batch:create-job 'Deliver outer event to consumer' deliver_outer_event_to_consumer internal deliver_outer_event_to_consumer
```

## Functionality

### How it works

* Some event(s) happens in Akeneo PIM. This triggers a mechanism to send those event(s) to a consumer
* Consumer receives a JSON payload which specifies event (the same data format like Akeneo PIM API uses)
* Akeneo PIM Events API Bundle uses separate job to deliver events to consumer
* The sending of events happens in real-time

### Event types delivered over Events API

| **Event** | **Description** |
| --------------------- |:----------------------------------:|
| category_created      | New category was created           |
| category_updated      | Existing category was updated      |
| category_deleted      | Existing category was deleted      |
| attribute_created     | New attribute was created          |
| attribute_updated     | Existing attribute was updated     |
| attribute_deleted     | Existing attribute was deleted     |
| family_created        | New family was created             |
| family_updated        | Existing family was updated        |
| family_created        | Existing family was deleted        |
| product_created       | New product was created            |
| product_updated       | Existing product was updated       |
| product_deleted       | Existing product was deleted       |
| product_model_created | New product model was created      |
| product_model_updated | Existing product model was updated |
| product_model_deleted | Existing product model was deleted |

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

#### Event Type Structure

| Field        | Type | Description                                                                                 |
| ------------ |:-------:|:----------------------------------------------------------------------------------------:|
| *event_type* | String  | Type of event which happened (see [event types](#Event types delivered over Events API)) |
| *payload*    | Object  | Contains information which represents the event                                          |
| *event_time* | Integer | Timestamp in seconds when the event was created                                          |

### Attention :heavy_exclamation_mark:

If Akeneo family contains variants, then during family update (or it's variants as well),
Akeneo will re-save related products. It will trigger sending *product_updated* events.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
