<?php

namespace Helper;

/**
 * Class Requests
 * @package Helper
 *
 * удаление
 * /api/Entity/Spending/<id>?method=delete
 *
 */
class Requests
{
    private array $data;

    /**
     * Requests constructor.
     * @param array $data
     */
    public function __construct($data = []) {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        if (($_REQUEST['method'] ?? ''))
        {
            // delete и прочие методы типа getSpendingAfterDate
            return $_REQUEST['method'];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            return 'get';
        }

        $parts = explode('/', $_SERVER['REQUEST_URI']);

        if (is_numeric($parts[3] ?? ''))
        {
            return 'edit';
        }

        return 'add';
    }

    /**
     * @param array $path
     * @param string $queryStr
     * @return string
     */
    public function handleRequest(array $path, string $queryStr) : string
    {
        $method = $this->getMethod();

        $class = '\Model\\' . $path[1] . '\\' . $path[2];

        parse_str($queryStr, $conditions);

        if (empty($conditions['id'])) {
            $conditions['id'] = is_numeric($path[3] ?? '') ? $path[3] : 0;
        }

        $params['conditions'] = $conditions;
        $params['data'] = $this->data;

        if (class_exists($class)) {

            $module = new $class;

            if (is_callable([$module, $method])) {
                try {
                    $resultRaw = call_user_func_array([$module, $method], [$params] ?? []);

                    if ($resultRaw === false) {
                        return json_encode('Error in params!');
                    }

                    return json_encode($resultRaw);

                }
                catch (\Error $e)
                {
                    return json_encode($e->getMessage());
                }
            }
            return json_encode('Method not found');
        }
        return json_encode('Class not found');
    }
}