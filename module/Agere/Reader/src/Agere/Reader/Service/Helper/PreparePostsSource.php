<?php
/**
 * Reader Service Helper
 *
 * @category Agere
 * @package Agere_Reader
 * @author Vlad Kozak <vk@agere.com.ua>
 */
namespace Agere\Reader\Service\Helper;

use Agere\Reader\Service\RssSyncService;
use Zend\Stdlib\Exception;

class PreparePostsSource extends AbstractPrepare
{
    /** @var RssSyncService */
    protected $service;

    public function __construct(RssSyncService $service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    public function prepare($news, $setting)
    {
        parent::prepare($news, $setting);
    }

    protected function prepareUnianDataExecute($news, $setting)
    {
        return $array = [
            $news->getId(),
            $setting['__currentSource']
        ];
    }
}