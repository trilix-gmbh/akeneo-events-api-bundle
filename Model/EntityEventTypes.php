<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

final class EntityEventTypes
{
    const CATEGORY_CREATED = 'category_created';

    const CATEGORY_UPDATED = 'category_updated';

    const CATEGORY_REMOVED = 'category_removed';

    const ATTRIBUTE_CREATED = 'attribute_created';

    const ATTRIBUTE_UPDATED = 'attribute_updated';

    const ATTRIBUTE_REMOVED = 'attribute_removed';

    const FAMILY_CREATED = 'family_created';

    const FAMILY_UPDATED = 'family_updated';

    const FAMILY_REMOVED = 'family_removed';

    const PRODUCT_CREATED = 'product_created';

    const PRODUCT_UPDATED = 'product_updated';

    const PRODUCT_REMOVED = 'product_removed';

    const PRODUCT_MODEL_CREATED = 'product_model_created';

    const PRODUCT_MODEL_UPDATED = 'product_model_updated';

    const PRODUCT_MODEL_REMOVED = 'product_model_removed';
}
