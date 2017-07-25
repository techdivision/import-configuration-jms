<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\LoggerFactory
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration;

use TechDivision\Import\Utils\LoggerKeys;
use TechDivision\Import\Utils\SwiftMailerKeys;
use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Configuration\LoggerConfigurationInterface;

/**
 * Logger factory implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class LoggerFactory
{

    /**
     * Creates a new logger instance based on the passed logger configuration.
     *
     * @param \TechDivision\Import\ConfigurationInterface                     $configuration       The system configuration
     * @param \TechDivision\Import\Configuration\LoggerConfigurationInterface $loggerConfiguration The logger configuration
     *
     * @return \Psr\Log\LoggerInterface The logger instance
     */
    public static function factory(
        ConfigurationInterface $configuration,
        LoggerConfigurationInterface $loggerConfiguration
    ) {

        // initialize the processors
        $processors = array();
        /** @var \TechDivision\Import\Configuration\Logger\ProcessorConfigurationInterface $processorConfiguration */
        foreach ($loggerConfiguration->getProcessors() as $processorConfiguration) {
            $reflectionClass = new \ReflectionClass($processorConfiguration->getType());
            $processors[] = $reflectionClass->newInstanceArgs(LoggerFactory::prepareConstructorArgs($reflectionClass, $processorConfiguration->getParams()));
        }

        // initialize the handlers
        $handlers = array();
        /** @var \TechDivision\Import\Configuration\Logger\HandlerConfigurationInterface $handlerConfiguration */
        foreach ($loggerConfiguration->getHandlers() as $handlerConfiguration) {
            // query whether or not, we've a swift mailer configuration
            if ($swiftMailerConfiguration = $handlerConfiguration->getSwiftMailer()) {
                // load the factory that creates the swift mailer instance
                $factory = $swiftMailerConfiguration->getFactory();
                // create the swift mailer instance
                $swiftMailer = $factory::factory($swiftMailerConfiguration);

                // load the generic logger configuration
                $bubble = $handlerConfiguration->getParam(LoggerKeys::BUBBLE);
                $logLevel = $handlerConfiguration->getParam(LoggerKeys::LOG_LEVEL);

                // load sender/receiver configuration
                $to = $swiftMailerConfiguration->getParam(SwiftMailerKeys::TO);
                $from = $swiftMailerConfiguration->getParam(SwiftMailerKeys::FROM);
                $subject = $swiftMailerConfiguration->getParam(SwiftMailerKeys::SUBJECT);
                $contentType = $swiftMailerConfiguration->getParam(SwiftMailerKeys::CONTENT_TYPE);

                // initialize the message template
                $message = $swiftMailer->createMessage()
                    ->setSubject(sprintf('[%s] %s', $configuration->getSystemName(), $subject))
                    ->setFrom($from)
                    ->setTo($to)
                    ->setBody('', $contentType);

                // initialize the handler node
                $reflectionClass = new \ReflectionClass($handlerConfiguration->getType());
                $handler = $reflectionClass->newInstanceArgs(array($swiftMailer, $message, $logLevel, $bubble));

            } else {
                // initialize the handler node
                $reflectionClass = new \ReflectionClass($handlerConfiguration->getType());

                // load the params
                $params = $handlerConfiguration->getParams();

                // set the default log level, if not already set explicitly
                if (!isset($params['level'])) {
                    $params['level'] = $configuration->getLogLevel();
                }

                // create the handler instance
                $handler = $reflectionClass->newInstanceArgs(LoggerFactory::prepareConstructorArgs($reflectionClass, $params));
            }

            // if we've a formatter, initialize the formatter also
            if ($formatterConfiguration = $handlerConfiguration->getFormatter()) {
                $reflectionClass = new \ReflectionClass($formatterConfiguration->getType());
                $handler->setFormatter($reflectionClass->newInstanceArgs(LoggerFactory::prepareConstructorArgs($reflectionClass, $formatterConfiguration->getParams())));
            }

            // add the handler
            $handlers[] = $handler;
        }

        // prepare the logger params
        $loggerParams = array(
            'name' => $loggerConfiguration->getChannelName(),
            'handlers' => $handlers,
            'processors' => $processors
        );

        // append the params from the logger configuration
        $loggerParams = array_merge($loggerParams, $loggerConfiguration->getParams());

        // initialize the logger instance itself
        $reflectionClass = new \ReflectionClass($loggerConfiguration->getType());
        return $reflectionClass->newInstanceArgs(LoggerFactory::prepareConstructorArgs($reflectionClass, $loggerParams));
    }

    /**
     * Prepare's the arguments for the passed reflection class by applying the values from the passed configuration array
     * to the apropriate arguments. If no value is found in the configuration, the constructor argument's default value is
     * used.
     *
     * @param \ReflectionClass $reflectionClass The reflection class to prepare the arguments for
     * @param array            $params          The constructor arguments from the configuration
     *
     * @return array The array with the constructor arguements
     */
    protected static function prepareConstructorArgs(\ReflectionClass $reflectionClass, array $params)
    {

        // prepare the array for the initialized arguments
        $initializedParams = array();

        // prepare the array for the arguments in camel case (in configuration we use a '-' notation)
        $paramsInCamelCase = array();

        // convert the configuration keys to camelcase
        foreach ($params as $key => $value) {
            $paramsInCamelCase[lcfirst(str_replace('-', '', ucwords($key, '-')))] = $value;
        }

        // prepare the arguments by applying the values from the configuration
        /** @var \ReflectionParameter $reflectionParameter */
        foreach ($reflectionClass->getConstructor()->getParameters() as $reflectionParameter) {
            if (isset($paramsInCamelCase[$paramName = $reflectionParameter->getName()])) {
                $initializedParams[$paramName] = $paramsInCamelCase[$paramName];
            } elseif ($reflectionParameter->isOptional()) {
                $initializedParams[$paramName] = $reflectionParameter->getDefaultValue();
            } else {
                $initializedParams[$paramName] = null;
            }
        }

        // return the array with the prepared arguments
        return $initializedParams;
    }
}
