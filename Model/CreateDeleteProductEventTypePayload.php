<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface;

class CreateDeleteProductEventTypePayload
{
    /**
     * @param GenericEventInterface $event
     * @return array
     */
    public function __invoke(GenericEventInterface $event): array
    {
        /** @var ProductInterface $product */
        $product = $event->getSubject();

        assert::that($product)->propertyExists('identifier');
        return ['identifier' => $product->getIdentifier()];
    }
}
