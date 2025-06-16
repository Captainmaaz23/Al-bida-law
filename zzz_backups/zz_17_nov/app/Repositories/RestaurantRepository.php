<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Repositories\BaseRepository;

/**
 * Class RestaurantRepository
 * @package App\Repositories
 * @version June 01, 2021, 3:01 pm UTC
 */
class RestaurantRepository extends BaseRepository {

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'phoneno',
        'email',
        'location',
        'status',
        'profile',
        'instagram'
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
        return Restaurant::class;
    }
}
