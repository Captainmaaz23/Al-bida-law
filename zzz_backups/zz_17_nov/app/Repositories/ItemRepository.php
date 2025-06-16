<?php

namespace App\Repositories;

use App\Models\Items;
use App\Repositories\BaseRepository;

/**
 * Class ItemRepository
 * @package App\Repositories
 * @version March 27, 2021, 8:07 pm UTC
 */
class ItemRepository extends BaseRepository {

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'menu_id',
        'price',
        'description',
        'selling_status',
        'availability',
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
        return Items::class;
    }
}
