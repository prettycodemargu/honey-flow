<?php

namespace Model\Entity;

use Db\Repository\Base as BaseRepository;

/**
 * Класс, позволяющий выполнять базывый CRUD для моделей
 *
 * Class Base
 * @package Model\Entity
 */
abstract class Base
{
    const CURRENCY_DIGITAL_CODE = 643; //руб
    const LIMIT = 10;

    const ERROR_REQUIRED_FIELD_ABSENTS = 'The field is required: ';
    const ERROR_FIELD_NOT_ALLOWED = 'The field is not allowed: ';

    protected BaseRepository $repository;

    protected array $fieldsRequired = [];

    protected array $fieldsNonRequired = [];

    protected array $defaultResult = [
        'status' => 200,
        'result_data' => null
    ];

    abstract protected function setRepository();

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->setRepository();
    }

    /**
     * @param array $data
     * @return string
     */
    protected function validateAndImproveFields(array &$data) : string
    {
        foreach($this->fieldsRequired as $fieldName => $defaultValue)
        {
            if (!isset($data[$fieldName])) {
                return self::ERROR_REQUIRED_FIELD_ABSENTS . $fieldName;
            }
        }

        foreach ($this->fieldsNonRequired as $fieldName => $defaultValue) {
            if ($defaultValue && !isset($data[$fieldName])) {
                $data[$fieldName] = $defaultValue;
            }
        }

        $allowedFields = array_merge($this->fieldsRequired, $this->fieldsNonRequired);

        foreach($data as $key => $value) {
            if (!isset($allowedFields[$key])) {
                return self::ERROR_FIELD_NOT_ALLOWED . $key;
            }
        }

        return '';
    }

    /**
     * @param array $params
     * data - массив со значениями полей сущности
     * @return array
     */
    public function add(array $params) : array
    {
        $result = $this->defaultResult;

        if (empty($params['data'])) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }

        $data = $params['data'];

        if ($error = $this->validateAndImproveFields($data)) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }

        try {
            $result['result_data']['id'] = $this->repository->add($data);
        } catch (\Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
        }

        $result['status'] = HTTP_CREATED;
        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    public function get(array $params = []) : array
    {
        $result = $this->defaultResult;

        $id = $params['conditions']['id'] ?? 0;

        $limit = 0;
        if (!($params['conditions']['no_limit'] ?? '') && !$id) {
            $limit = self::LIMIT;
        }

        $offset = $params['conditions']['offset'] ?? 0;

        try {
            $result['result_data'] = $this->repository->get($id, $limit, $offset);
        } catch (\Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
        }

        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    public function edit(array $params) : array
    {
        $result = $this->defaultResult;

        if (empty($params['conditions']['id'])) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }

        if (empty($params['data'])) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }

        $allowedFields = array_merge($this->fieldsRequired, $this->fieldsNonRequired);

        foreach ($params['data'] as $key => $value)
        {
            if (!isset($allowedFields[$key])) {
                $result['status'] = HTTP_BAD_REQUEST;
                return $result;
            }
        }

        try {
            if ($this->repository->edit($params['conditions']['id'], $params['data'])) {
                $result['status'] = HTTP_ACCEPTED;
            } else {
                $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            }
        } catch (\Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
        }

        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete(array $params) : array
    {
        $result = $this->defaultResult;

        if (empty($params['conditions']['id'])) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }

        try {
            $this->repository->delete($params['conditions']['id']);
        } catch (\Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
        }

        $result['status'] = HTTP_NO_CONTENT;
        return $result;
    }
}