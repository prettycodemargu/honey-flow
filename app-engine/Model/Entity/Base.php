<?php

namespace Model\Entity;

use Db\Repository\Base as BaseRepository;

/**
 * Класс, позволяющий выполнять базывый CRUD для моделей
 *
 * Class Base
 * @package Model\Entity
 */
abstract class Base {

    const CURRENCY_DIGITAL_CODE = 643; //руб
    const LIMIT = 10;

    const ERROR_REQUIRED_FIELD_ABSENTS = 'The field is required: ';
    const ERROR_FIELD_NOT_ALLOWED = 'The field is not allowed: ';

    const ERROR_EMPTY_SET = 'Empty set';
    const ERROR_EMPTY_ID = 'Empty id';

    protected BaseRepository $repository;

    protected array $fieldsRequired = [];

    protected array $fieldsNonRequired = [];

    protected array $defaultResult = [
        'success' => false,
        'error' => '',
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
    protected function validateAndImproveFields(array &$data) : string {

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
    public function add(array $params) : array {

        $result = $this->defaultResult;

        if (empty($params['data'])) {
            $result['error'] = self::ERROR_EMPTY_SET;
            return $result;
        }

        $data = $params['data'];

        if ($result['error'] = $this->validateAndImproveFields($data)) {
            return $result;
        }

        try {
            $result['result_data']['id'] = $this->repository->add($data);
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

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
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
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
            $result['error'] = self::ERROR_EMPTY_ID;
            return $result;
        }

        if (empty($params['data'])) {
            $result['error'] = self::ERROR_EMPTY_SET;
            return $result;
        }

        $allowedFields = array_merge($this->fieldsRequired, $this->fieldsNonRequired);

        foreach ($params['data'] as $key => $value)
        {
            if (!isset($allowedFields[$key])) {
                $result['error'] = self::ERROR_FIELD_NOT_ALLOWED . $key;
                return $result;
            }
        }

        try {
            $result['success'] = $this->repository->edit($params['conditions']['id'], $params['data']);
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
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
            $result['error'] = self::ERROR_EMPTY_ID;
            return $result;
        }

        try {
            $this->repository->delete($params['conditions']['id']);
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}