<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Restaurant extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $img = $this->profile;
        if ($img == "restaurant.png") {

            $img = uploads('defaults') . '/' . $img;
        }
        else {
            $img = uploads('restaurants') . '/' . $img;
        }
        //  return parent::toArray($request);

        $status = $this->status;
        $distance = '';
        $km = ' km';
        (($status == 1) ? $status = true : $status = false);

        ((isset($this->distance)) ? $distance = round($this->distance, 2) : $distance = null);
        $data = [
            'id'                 => $this->id,
            'name'               => $this->name,
            'arabic_name'        => $this->arabic_name,
            'location'           => $this->location,
            'phoneno'            => $this->phoneno,
            'email'              => $this->email,
            'website_link'       => $this->website_link,
            'image'              => $img,
            'qrcode'             => $this->qrcode,
            'description'        => $this->description,
            'arabic_description' => $this->arabic_description,
            'status'             => $status,
            'is_featured'        => $this->is_featured,
            'distance'           => $distance . $km,
        ];
        (($distance != null) ? $data += ['distance' => $distance] : '');
        return $data;
    }
}
