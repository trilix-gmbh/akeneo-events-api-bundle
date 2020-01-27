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
use Trilix\EventsApiBundle\Model\CreateRemoveEventTypePayload;
use Trilix\EventsApiBundle\Model\GenericRemoveEntityEventInterface;

class CreateRemoveEventTypePayloadTest extends TestCase
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

        $actualPayload = (new CreateRemoveEventTypePayload())->__invoke($event);
        self::assertSame($expectedPayload, $actualPayload);
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionIfSubjectDoesNotContainsCodeProperty(): void
    {
        $removeProductEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Product
            {
                return (new Product());
            }
        };

        $this->expectException(InvalidArgumentException::class);

        (new CreateRemoveEventTypePayload())->__invoke($removeProductEvent);
    }

    /**
     * @return array
     */
    public function supportedEventsDataProvider(): array
    {
        $removeCategoryEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Category
            {
                return (new Category())->setCode('categoryCode');
            }
        };

        $removeAttributeEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Attribute
            {
                return (new Attribute())->setCode('attributeCode');
            }
        };

        $removeFamilyEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Family
            {
                return (new Family())->setCode('familyCode');
            }
        };

        $removeProductModelEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): ProductModel
            {
                $productModel = new ProductModel();
                $productModel->setCode('productModelCode');
                return $productModel;
            }
        };

        return [
            [$removeCategoryEvent, 'categoryCode'],
            [$removeAttributeEvent, 'attributeCode'],
            [$removeFamilyEvent, 'familyCode'],
            [$removeProductModelEvent, 'productModelCode']
        ];
    }
}
