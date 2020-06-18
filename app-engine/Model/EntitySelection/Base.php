<?php


namespace Model\EntitySelection;

/**
 * Class Base
 * @package Model\EntitySelection
 *
 * Базовый класс получения наборов сущностей
 */
abstract class Base
{
    protected array $defaultResult = [
        'success' => false,
        'error' => '',
        'result_data' => null
    ];

    const ERROR_WRONG_PARAM_TYPE = 'Неверный тип переданного параметра';

    /**
     * Главный метод для получения набора сущностей для последующей визуализации
     *
     * @param array $params
     * @return array
     */
    abstract public function getSelection(array $params) : array;
}