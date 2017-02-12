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

class PreparePosts extends AbstractPrepare
{
    /** @var RssSyncService */
    protected $_service;

    protected $_localSettings = [
        'unian' => [
            'userId' => '30141',
        ],
    ];

    protected $_locale = 'ru_RU';

    public function __construct(RssSyncService $service)
    {
        $this->_service = $service;
    }

    protected function getService()
    {
        return $this->_service;
    }

    public function prepare($news, $setting)
    {
        parent::prepare($news, $setting);
    }

    protected function prepareUnianDataExecute($news, $setting)
    {
        return $array = [
            $news->getTitle(),
            $this->preg($news->getDescription()),
            $this->preg($news->getContent()),
            $setting['__currentSource'],
            $news->getDateCreated()->format('Y-m-d H:i:s'),
            $news->getDateModified()->format('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            $this->_localSettings['unian']['userId'],
            $this->_locale
        ];
    }

    protected function preg($string) {
        return (preg_replace('/^.+УНИАН[.]\s+/', '', $string));
    }
}