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
use Trilix\EventsApiBundle\Model\CreateDeleteEventTypePayload;
use Trilix\EventsApiBundle\Model\GenericRemoveEntityEventInterface;

class CreateDeleteEventTypePayloadTest extends TestCase
{
    /**
     * @test
     * @dataProvider supportedEventsDataProvider
     *
     * @param $event
     * @param $entityCode
     */
    public function createsPayload($event, $entityCode): void
    {
        $expectedPayload = ['code' => $entityCode];

        $actualPayload = (new CreateDeleteEventTypePayload())->__invoke($event);
        self::assertSame($expectedPayload, $actualPayload);
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionIfSubjectDoesNotContainsCodeProperty(): void
    {
        $deleteProductEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Product
            {
                return (new Product());
            }
        };

        $this->expectException(InvalidArgumentException::class);

        (new CreateDeleteEventTypePayload())->__invoke($deleteProductEvent);
    }

    /**
     * @return array
     */
    public function supportedEventsDataProvider(): array
    {
        $deleteCategoryEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Category
            {
                return (new Category())->setCode('categoryCode');
            }
        };

        $deleteAttributeEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Attribute
            {
                return (new Attribute())->setCode('attributeCode');
            }
        };

        $deleteFamilyEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Family
            {
                return (new Family())->setCode('familyCode');
            }
        };

        $deleteProductModelEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): ProductModel
            {
                $productModel = new ProductModel();
                $productModel->setCode('productModelCode');
                return $productModel;
            }
        };

        return [
            [$deleteCategoryEvent, 'categoryCode'],
            [$deleteAttributeEvent, 'attributeCode'],
            [$deleteFamilyEvent, 'familyCode'],
            [$deleteProductModelEvent, 'productModelCode']
        ];
    }
}
