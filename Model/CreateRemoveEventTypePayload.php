<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Akeneo\Pim\Enrichment\Component\Category\Model\CategoryInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductModelInterface;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Akeneo\Pim\Structure\Component\Model\FamilyInterface;

class CreateRemoveEventTypePayload
{
    /**
     * @param GenericEventInterface $event
     * @return array
     */
    public function __invoke(GenericEventInterface $event): array
    {
        /** @var CategoryInterface|AttributeInterface|FamilyInterface|ProductModelInterface $entity */
        $entity = $event->getSubject();

        assert::that($entity)->propertyExists('code');
        return ['code' => $entity->getCode()];
    }
}
