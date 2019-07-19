<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Akeneo\Pim\Enrichment\Component\Category\Model\CategoryInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductModelInterface;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Akeneo\Pim\Structure\Component\Model\FamilyInterface;
use Trilix\EventsApiBundle\EventType\EventTypeConfiguration;
use Trilix\EventsApiBundle\EventType\EventTypeConfigurationInterface;

class PimEventTypeConfigurationFactories
{
    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function categoryCreatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::CATEGORY_CREATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof CategoryInterface && $event instanceof GenericCreateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function categoryUpdatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::CATEGORY_UPDATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof CategoryInterface && $event instanceof GenericUpdateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function categoryRemovedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::CATEGORY_REMOVED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof CategoryInterface && $event instanceof GenericRemoveEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function attributeCreatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::ATTRIBUTE_CREATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof AttributeInterface && $event instanceof GenericCreateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function attributeUpdatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::ATTRIBUTE_UPDATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof AttributeInterface && $event instanceof GenericUpdateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function attributeRemovedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::ATTRIBUTE_REMOVED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof AttributeInterface && $event instanceof GenericRemoveEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function familyCreatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::ATTRIBUTE_CREATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof FamilyInterface && $event instanceof GenericCreateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function familyUpdatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::ATTRIBUTE_UPDATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof FamilyInterface && $event instanceof GenericUpdateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function familyRemovedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::ATTRIBUTE_REMOVED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof FamilyInterface && $event instanceof GenericRemoveEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function productCreatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::PRODUCT_CREATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof ProductInterface && $event instanceof GenericCreateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function productUpdatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::PRODUCT_UPDATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof ProductInterface && $event instanceof GenericUpdateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function productRemovedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::PRODUCT_REMOVED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof ProductInterface && $event instanceof GenericRemoveEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function productModelCreatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::PRODUCT_MODEL_CREATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof ProductModelInterface && $event instanceof GenericCreateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function productModelUpdatedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::PRODUCT_MODEL_UPDATED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof ProductModelInterface && $event instanceof GenericUpdateEntityEventInterface;
            },
            $createEventTypePayload
        );
    }

    /**
     * @param CreateEventTypePayload $createEventTypePayload
     * @return EventTypeConfigurationInterface
     */
    public static function productModelRemovedEventTypeConfiguration(CreateEventTypePayload $createEventTypePayload): EventTypeConfigurationInterface
    {
        return new EventTypeConfiguration(
            EntityEventTypes::PRODUCT_MODEL_REMOVED,
            static function (GenericEventInterface $event) {
                return $event->getSubject() instanceof ProductModelInterface && $event instanceof GenericRemoveEntityEventInterface;
            },
            $createEventTypePayload
        );
    }
}
