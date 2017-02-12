<?php
/**
 * Reader Service 
 *
 * @category Agere
 * @package Agere_Reader
 * @author Vlad Kozak <vk@agere.com.ua>
 */
namespace Agere\Reader\Service;

use Zend\Feed\Reader\Reader;
use Agere\Core\Service\DomainServiceAbstract;
use Agere\Reader\Model\PostsSource;
use Agere\Core\Service\ServiceManagerAwareTrait;
use Zend\Stdlib\Exception;

class RssSyncService extends DomainServiceAbstract
{
    use ServiceManagerAwareTrait;

    protected $namespace = 'Agere\\Reader\\Service\\Helper';

    protected $entity = PostsSource::class;

    protected $_settings = [
        [
            '__table' => 'posts_source',
            '__fields' => [
                'guid',
                'source'
            ],
            '__helper' => 'postsSource'
        ],
        [
            '__table' => 'posts',
            '__fields' => [
                'title',
                'description',
                'content',
                'status',
                'create_at',
                'update_at',
                'publish_at',
                'employee_id',
                'locale'
            ],
            '__helper' => 'posts'
        ],
        [
            '__table' => 'posts_rel_categories',
            '__fields' => [
                'post_id',
                'posts_category_id'
            ],
            '__helper' => 'postsRelCategories'
        ]
    ];

    public function prepareData($sources)
    {
        foreach (array_keys($sources) as $keysSource) {
            foreach (array_keys($sources[$keysSource]) as $keysCurrentSource) {
                if (method_exists($this, $method = 'getData' . ucfirst($keysSource))) {
                    $news = $this->{$method}($sources[$keysSource][$keysCurrentSource]);
                } else {
                    throw new Exception\RuntimeException(sprintf(
                        'This method %s does not exists in class %s', $method, get_class($this)
                    ));
                }
                foreach ($news as $item) {
                    if (!$post = $this->getRepository()->findOneBy(['guid' => $item->getId()])) {
                        foreach ($this->_settings as $setting) {
                            if (isset($setting['__helper'])) {
                                $setting['__currentSource'] = $keysCurrentSource;
                                $setting['__source'] = $keysSource;
                                $this->getHelper($setting['__helper'], 'prepare')->prepare($item, $setting);
                            }
                        }
                    }
                }
            }
        }
    }

    protected function getDataUnian($url)
    {
        $client = new \Zend\Http\Client($url);
        $response = $client->send();
        $content = str_replace('windows-1251', 'utf-8', $response->getBody());
        $data = Reader::importString($content);
        return $data;
    }

    public function getPdo()
    {
        $sm = $this->getServiceManager();
        $em = $sm->get('Doctrine\ORM\EntityManager');

        return $em->getConnection()->getWrappedConnection();
    }

    public function getHelper($name, $pool)
    {
        static $helpers = [];
        $key = $pool . $name;
        if (isset($helpers[$key])) {
            return $helpers[$key];
        }
        if (!class_exists($class = $this->namespace . '\\' . ucfirst($pool) . ucfirst($name))) {
            throw new Exception\RuntimeException(sprintf('Import helper [%s] not exists', $class));
        }

        return $helpers[$key] = new $class($this);
    }

    public function getSettings()
    {
        return $this->_settings;
    }
}