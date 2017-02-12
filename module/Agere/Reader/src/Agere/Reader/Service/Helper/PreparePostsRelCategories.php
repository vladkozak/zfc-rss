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

class PreparePostsRelCategories extends AbstractPrepare
{
    /** @var RssSyncService */
    protected $service;

    protected $_localSettings = [
        'unian' => [
            'category' => [
                'unian_business' => '2',
                'unian_news' => '21'
            ]
        ],
    ];

    public function __construct(RssSyncService $service)
    {
        $this->service = $service;
    }

    protected function getService()
    {
        return $this->service;
    }

    public function prepare($news, $setting)
    {
        foreach (array_keys($this->_localSettings['unian']['category']) as $key) {
            if (($setting['__currentSource']) != $key) {
                return;
            }
            parent::prepare($news, $setting, $key);
        }
    }

    protected function prepareUnianDataExecute($news, $setting, $key)
    {
        $service = $this->getService();
        $pdo = $service->getPdo();

        return $array = [
            $pdo->lastInsertId(),
            $this->_localSettings['unian']['category'][$key]
        ];
    }
}