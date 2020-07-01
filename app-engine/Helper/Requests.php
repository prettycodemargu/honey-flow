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
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            return 'get';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return 'add';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            return 'edit';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return 'delete';
        }

        return '';
    }

    /**
     * @param array $path
     * @param string $queryStr
     * @return array
     */
    public function handleRequest(array $path, string $queryStr) : array
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
                        return [
                            'status' => HTTP_BAD_REQUEST,
                            'result_data' => ''
                        ];
                    }

                    return $resultRaw;
                }
                catch (\Error $e)
                {
                    return [
                        'status' => HTTP_INTERNAL_SERVER_ERROR,
                        'result_data' => ''
                    ];
                }
            }
        }
        return [
            'status' => HTTP_BAD_REQUEST,
            'result_data' => ''
        ];
    }
}