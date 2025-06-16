<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $img = $this->image;
        if ($img == "item.png") {

            $img = uploads('defaults') . '/' . $img;
        }
        else {
            $img = uploads('items') . '/' . $img;
        }
        $status = $this->status;
        (($status == 1) ? $status = true : $status = false);
        return [
            'id'             => $this->id,
            'cat_id'         => $this->cat_id,
            'name'           => $this->name,
            'ar_name'        => $this->ar_name,
            'price'          => $this->price,
            'discount'       => $this->discount,
            'total_value'    => $this->total_value,
            'description'    => $this->description,
            'ar_description' => $this->ar_description,
            'image'          => $img,
            'status'         => $status,
            'variations'     => $this->variations,
        ];
    }
}
