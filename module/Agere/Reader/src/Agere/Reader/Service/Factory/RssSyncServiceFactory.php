<?php
/**
 * Reader Service Factory
 *
 * @category Agere
 * @package Agere_Reader
 * @author Vlad Kozak <vk@agere.com.ua>
 */
namespace Agere\Reader\Service\Factory;

use Agere\Reader\Service\RssSyncService;

class RssSyncServiceFactory
{
    public function __invoke($cm)
    {
        $service = new RssSyncService();
        $service->setServiceManager($cm);

        return $service;
    }
}