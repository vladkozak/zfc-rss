<?php
/**
 * Reader Controller Factory
 *
 * @category Agere
 * @package Agere_Reader
 * @author Vlad Kozak <vk@agere.com.ua>
 */
namespace Agere\Reader\Controller\Factory;

use Agere\Reader\Controller\ReaderController;

class ReaderControllerFactory
{
    public function __invoke($cm)
    {
        $sm = $cm->getServiceLocator();
        $controller = new ReaderController();
        $controller->setServiceManager($sm);

        return $controller;
    }
}
