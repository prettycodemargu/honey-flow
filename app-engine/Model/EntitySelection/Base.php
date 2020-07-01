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
        'status' => HTTP_OK,
        'result_data' => null
    ];


    /**
     * Главный метод для получения набора сущностей для последующей визуализации
     *
     * @param array $params
     * @return array
     */
    abstract public function get(array $params) : array;
}