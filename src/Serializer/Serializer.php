<?php

/*
 * Copyright 2013 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace TechDivision\Import\Configuration\Jms\Serializer;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\Exception\UnsupportedFormatException;
use Metadata\MetadataFactoryInterface;
use PhpCollection\MapInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\TypeParser;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Exception\RuntimeException;

/**
 * Serializer Implementation.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class Serializer implements SerializerInterface
{
    private $factory;
    private $handlerRegistry;
    private $objectConstructor;
    private $dispatcher;
    private $typeParser;

    /** @var \PhpCollection\MapInterface */
    private $serializationVisitors;

    /** @var \PhpCollection\MapInterface */
    private $deserializationVisitors;

    private $navigator;

    /**
     * Constructor.
     *
     * @param \Metadata\MetadataFactoryInterface $factory
     * @param \JMS\Serializer\Handler\HandlerRegistryInterface $handlerRegistry
     * @param \JMS\Serializer\Construction\ObjectConstructorInterface  $objectConstructor
     * @param \PhpCollection\MapInterface<VisitorInterface> $serializationVisitors
     * @param \PhpCollection\MapInterface<VisitorInterface> $deserializationVisitors
     * @param \JMS\Serializer\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \JMS\Serializer\TypeParser $typeParser
     */
    public function __construct(
        MetadataFactoryInterface $factory,
        HandlerRegistryInterface $handlerRegistry,
        ObjectConstructorInterface $objectConstructor,
        MapInterface $serializationVisitors,
        MapInterface $deserializationVisitors,
        EventDispatcherInterface $dispatcher = null,
        TypeParser $typeParser = null
    ) {
        $this->factory = $factory;
        $this->handlerRegistry = $handlerRegistry;
        $this->objectConstructor = $objectConstructor;
        $this->dispatcher = $dispatcher;
        $this->typeParser = $typeParser ?: new TypeParser();
        $this->serializationVisitors = $serializationVisitors;
        $this->deserializationVisitors = $deserializationVisitors;

        $this->navigator = new GraphNavigator($this->factory, $this->handlerRegistry, $this->objectConstructor, $this->dispatcher);
    }

    public function serialize($data, $format, SerializationContext $context = null)
    {
        if ( ! $this->serializationVisitors->containsKey($format)) {
            throw new UnsupportedFormatException(sprintf('The format "%s" is not supported for serialization.', $format));
        }

        if (null === $context) {
            $context = new SerializationContext();
        }

        $context->initialize(
            $format,
            $visitor = $this->serializationVisitors->get($format)->get(),
            $this->navigator,
            $this->factory
        );

        $visitor->setNavigator($this->navigator);
        $this->navigator->accept($visitor->prepare($data), null, $context);

        return $visitor->getResult();
    }

    public function deserialize($data, $type, $format, DeserializationContext $context = null)
    {
        if ( ! $this->deserializationVisitors->containsKey($format)) {
            throw new UnsupportedFormatException(sprintf('The format "%s" is not supported for deserialization.', $format));
        }

        if (null === $context) {
            $context = new DeserializationContext();
        }

        $context->initialize(
            $format,
            $visitor = $this->deserializationVisitors->get($format)->get(),
            $this->navigator,
            $this->factory
        );

        $visitor->setNavigator($this->navigator);
        $navigatorResult = $this->navigator->accept($visitor->prepare($data), $this->typeParser->parse($type), $context);

        // This is a special case if the root is handled by a callback on the object iself.
        if ((null === $visitorResult = $visitor->getResult()) && null !== $navigatorResult) {
            return $navigatorResult;
        }

        return $visitorResult;
    }
    /**
     * Converts objects to an array structure.
     *
     * This is useful when the data needs to be passed on to other methods which expect array data.
     *
     * @param mixed $data anything that converts to an array, typically an object or an array of objects
     *
     * @return array
     */
    public function toArray($data, SerializationContext $context = null)
    {
        if (null === $context) {
            $context = new SerializationContext();
        }
        return $this->serializationVisitors->get('json')
        ->map(function(JsonSerializationVisitor $visitor) use ($context, $data) {
            $this->visit($visitor, $context, $data, 'json');
            $result = $this->convertArrayObjects($visitor->getRoot());
            if ( ! is_array($result)) {
                throw new RuntimeException(sprintf(
                    'The input data of type "%s" did not convert to an array, but got a result of type "%s".',
                    is_object($data) ? get_class($data) : gettype($data),
                    is_object($result) ? get_class($result) : gettype($result)
                    ));
            }
            return $result;
        })
        ->get();
    }
    /**
     * Restores objects from an array structure.
     *
     * @param array $data
     * @param string $type
     *
     * @return mixed this returns whatever the passed type is, typically an object or an array of objects
     */
    public function fromArray(array $data, $type, DeserializationContext $context = null)
    {
        if (null === $context) {
            $context = new DeserializationContext();
        }
        return $this->deserializationVisitors->get('json')
        ->map(function(JsonDeserializationVisitor $visitor) use ($data, $type, $context) {
            $navigatorResult = $this->visit($visitor, $context, $data, 'json', $this->typeParser->parse($type));
            return $this->handleDeserializeResult($visitor->getResult(), $navigatorResult);
        })
        ->get();
    }

    private function visit(VisitorInterface $visitor, Context $context, $data, $format, array $type = null)
    {
        $context->initialize(
            $format,
            $visitor,
            $this->navigator,
            $this->factory
            );
        $visitor->setNavigator($this->navigator);
        return $this->navigator->accept($data, $type, $context);
    }

    private function handleDeserializeResult($visitorResult, $navigatorResult)
    {
        // This is a special case if the root is handled by a callback on the object itself.
        if (null === $visitorResult && null !== $navigatorResult) {
            return $navigatorResult;
        }
        return $visitorResult;
    }

    private function convertArrayObjects($data)
    {
        if ($data instanceof \ArrayObject) {
            $data = (array) $data;
        }
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->convertArrayObjects($v);
            }
        }
        return $data;
    }

    /**
     * @return MetadataFactoryInterface
     */
    public function getMetadataFactory()
    {
        return $this->factory;
    }
}
