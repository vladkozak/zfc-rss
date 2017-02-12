<?php
/**
 * Reader Service Helper
 *
 * @category Agere
 * @package Agere_Reader
 * @author Vlad Kozak <vk@agere.com.ua>
 */
namespace Agere\Reader\Service\Helper;

use Zend\Stdlib\Exception;

abstract class AbstractPrepare
{
    protected function prepare($news, $setting, $key = null)
    {
        $service = $this->getService();
        $pdo = $service->getPdo();
        if (method_exists($this, $method = 'prepare' . ucfirst($setting['__source']) . 'DataExecute')) {
            if ($key) {
                $array = $this->{$method}($news, $setting, $key);
            } else {
                $array = $this->{$method}($news, $setting);
            }
        } else {
            throw new Exception\RuntimeException(
                sprintf(
                    'This method %s does not exists in class %s', $method, get_class($this)
                )
            );
        }
        if (isset($setting['__fields'])) {
            $lineFields = '';
            foreach ($setting['__fields'] as $field) {
                $lineFields .= '`' . $field . '`, ';
            }
            $lineFields = rtrim(($lineFields), ', ');
        }
        // Search dublicate
        if ($setting['__table'] == 'posts') {
            $titles = "SELECT COUNT(*) FROM posts WHERE title = :title";
            $stmt = $pdo->prepare($titles);
            $stmt->execute([":title" => current($array)]);
            $res = $stmt->fetchColumn();
        }
        if (($res == 0) || ($setting['__table'] != 'posts')) {
            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $setting['__table'], $lineFields, rtrim(str_repeat('?,', (count($setting['__fields']))), ',')
            );
            $stmt = $pdo->prepare($sql);
            $stmt->execute($array);
        }

        return true;
    }
}