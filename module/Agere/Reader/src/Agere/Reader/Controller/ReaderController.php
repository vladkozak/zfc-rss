<?php
/**
 * Reader Controller
 *
 * @category Agere
 * @package Agere_Reader
 * @author Vlad Kozak <vk@agere.com.ua>
 */
namespace Agere\Reader\Controller;

use Agere\Core\Service\ServiceManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Feed\Reader\Exception;

class ReaderController extends AbstractActionController
{
    use ServiceManagerAwareTrait;

    public function syncAction()
    {
        $sm = $this->getServiceManager();
        $config = $sm->get('config');
        $sources = $config['sources'];
        $this->getService()->prepareData($sources);
        //@todo Записувати у логи інформацію (поточний час)!
        die(__METHOD__);
    }

    public function getService()
    {
        return $this->getServiceManager()->get('RssSyncService');
    }
}