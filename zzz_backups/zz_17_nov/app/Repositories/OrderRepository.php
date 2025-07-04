<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\BaseRepository;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version March 27, 2021, 8:11 pm UTC
 */
class OrderRepository extends BaseRepository {

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'total',
        'store_id',
        'status'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable() {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     * */
    public function model() {
        return Order::class;
    }
}
