# Akeneo PIM Events API

The Events API is a smooth and easy way to build integrations that respond to activities in Akeneo PIM. 
All you need is PIM Events API Bundle and an endpoint where to send Akeneo PIM events.

## Table of contents
* [Getting Started](#Getting-Started)
* [Functionality](#Functionality)
* [Roadmap](#Roadmap)
* [License](#License)

## Getting Started

### Requirements

* Akeneo PIM >= 3.0 (CE & EE)

### Installation

Install via composer:

```bash
php composer.phar require trilix/akeneo-events-api-bundle:^0.4.0-dev
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
default_events_api_app_uri: 'endpoint_url'
```

where `endpoint_url` is an endpoint url of consumer which accepts events from Akeneo PIM.

Add the following lines at the end of *app/config/config.yml*:

```yaml
trilix_events_api:
    applications:
        default:
            uri: "%default_events_api_app_uri%"
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

### Supported Event Types

* **Category**
    * Create
    * Update
    * Delete
* **Attribute**   
    * Create
    * Update
    * Delete
* **Family**   
    * Create
    * Update
    * Delete
* **Product**   
    * Create
    * Update
    * Delete
* **Product Model**   
    * Create
    * Update
    * Delete

### Example of delivered event

```json
{
  "event": {
    "payload": {
      "code": "cameras",
      "labels": {
        "de_DE": "Cameras new name",
        "en_US": "Cameras",
        "fr_FR": "Caméras"
      },
      "parent": "master"
    },
    "event_type": "category_updated"
  }
}
```

#### Event Structure

| Field        | Description                                                             |
| ------------ |:-----------------------------------------------------------------------:| 
| *event_type* | Type of event which happened (see [Event Types](#Supported-event-types))| 
| *payload*    | Contains information which represents the event                         |  

### Attention :heavy_exclamation_mark:

If Akeneo family contains variants, then during family update (or it's variants as well),
Akeneo will re-save related products. It will trigger sending *Products Update* events.   

## Roadmap

* Possibility to select event types to subscribe   
* URL Verification Handshake
* Support for Multiple endpoints
* Rate limiting
* Support for Akeneo Enterprise event types
* Custom events
    * Import 
    * Export
    * Mass edit
    * etc

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
