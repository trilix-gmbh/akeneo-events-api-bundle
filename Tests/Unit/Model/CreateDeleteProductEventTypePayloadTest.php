<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Assert\InvalidArgumentException;
use Akeneo\Pim\Enrichment\Component\Category\Model\Category;
use Akeneo\Pim\Structure\Component\Model\Attribute;
use Akeneo\Pim\Structure\Component\Model\Family;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductModel;
use Akeneo\Pim\Enrichment\Component\Product\Model\Product;
use Akeneo\Pim\Enrichment\Component\Product\Value\ScalarValue;
use Trilix\EventsApiBundle\Model\CreateDeleteProductEventTypePayload;
use Trilix\EventsApiBundle\Model\GenericRemoveEntityEventInterface;

class CreateDeleteProductEventTypePayloadTest extends TestCase
{
    /**
     * @test
     */
    public function createsPayload(): void
    {
        $deleteProductEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Product
            {
                return (new Product())->setIdentifier(ScalarValue::value('sku', 'test-123'));
            }
        };

        $expectedPayload = ['identifier' => 'test-123'];

        $actualPayload = (new CreateDeleteProductEventTypePayload())->__invoke($deleteProductEvent);
        self::assertSame($expectedPayload, $actualPayload);
    }

    /**
     * @test
     * @dataProvider notSupportedEventsDataProvider
     *
     * @param $event
     */
    public function throwsInvalidArgumentExceptionIfSubjectDoesNotContainsIdentifierProperty($event): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new CreateDeleteProductEventTypePayload())->__invoke($event);
    }

    /**
     * @return array
     */
    public function notSupportedEventsDataProvider(): array
    {
        $deleteCategoryEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Category
            {
                return new Category();
            }
        };

        $deleteAttributeEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Attribute
            {
                return new Attribute();
            }
        };

        $deleteFamilyEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Family
            {
                return new Family();
            }
        };

        $deleteProductModelEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): ProductModel
            {
                return new ProductModel();
            }
        };

        return [
            [$deleteCategoryEvent],
            [$deleteAttributeEvent],
            [$deleteFamilyEvent],
            [$deleteProductModelEvent]
        ];
    }
}
