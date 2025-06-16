<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;

class OrderSeeder extends Seeder {

    public function run() {

        $rest_id = 1;

        $startDate = Carbon::now()->subYears(3); // Start date, two years ago
        $endDate = Carbon::now(); // Current date

        while ($startDate->lessThanOrEqualTo($endDate)) {

            $user_id = rand(1, 1000);
            $table_id = rand(1, 1000);
            $item_1 = rand(1, 50);
            $item_1_qty = rand(1, 5);
            $item_2 = rand(51, 100);
            $item_2_qty = rand(1, 5);
            $status = rand(1, 2);
            $time = $startDate->toDateTimeString();

            $items = [
                [
                    "item_id"  => $item_1,
                    "quantity" => $item_1_qty
                ],
                [
                    "item_id"  => $item_2,
                    "quantity" => $item_2_qty
                ]
            ];

            $orderData = $this->prepareOrderData($table_id, $rest_id, $user_id, $startDate);
            $order = $this->createOrder($orderData);

            $order_no = $this->generateOrderNo($order->id, strtotime($time));
            $order_value = 0;
            foreach ($items as $item) {
                $order_value += $this->processItem($item, $order->id);
            }

            $final_value = $this->calculateOrderTotal($order_value);
            $this->updateOrder($order, $order_no, $order_value, $final_value, $startDate, $status);

            $interval = rand(35, 50);
            $startDate->addMinutes($interval);
        }
    }

    private function prepareOrderData($table_id, $rest_id, $user_id, $startDate) {
        $time = $startDate->toDateTimeString();
        $pickup_time = $startDate->addMinutes(20)->toDateTimeString();
        return [
            'rest_id'          => $rest_id,
            'table_id'         => $table_id,
            'user_id'          => $user_id,
            'lat'              => $this->lat ?? 0,
            'lng'              => $this->lng ?? 0,
            'pickup_time'      => $pickup_time,
            'confirmed_time'   => $time,
            'accepted_time'    => $time,
            'scheduled_time'   => $time,
            'preparation_time' => $time,
            'ready_time'       => $pickup_time,
            'picked_time'      => $pickup_time,
            'collected_time'   => $pickup_time,
            'pay_method'       => 'cash',
            'pay_method_id'    => 0,
            'pay_status'       => 0,
            'transaction_id'   => 0,
            'order_value'      => 0,
            'promo_id'         => 0,
            'promo_value'      => 0,
            'total_value'      => 0,
            'vat_included'     => 0,
            'vat_value'        => 0,
            'final_value'      => 0,
        ];
    }

    private function createOrder($data) {
        $order = new Order();
        $order->fill($data);
        $order->save();
        return $order;
    }

    private function generateOrderNo($order_id, $time) {
        $day = sprintf('%02d', date('d', $time));
        $month = sprintf('%02d', date('m', $time));
        $year = date('y', $time);
        $code = sprintf("%04u", $order_id);
        return $year . $month . $day . "-" . $code;
    }

    private function processItem($item, $order_id) {
        $notes = "This is Test Comment and Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
        $item_id = $item['item_id'];
        $quantity = (float) $item['quantity'];
        $item_value = 0;
        $discount_value = 0;
        $total_value = 0;

        $item_details = get_items_prices_for_orders($item_id);
        if ($item_details) {
            $item_value = (float) $item_details['price'];
            $discount_value = $this->calculateDiscount($item_details, $item_value);
            $total_value = ($item_value - $discount_value);
        }

        $total_value *= $quantity;
        $total_value = round($total_value, 2);

        $this->saveOrderDetail($order_id, $item, $notes, $quantity, $item_value, $discount_value, $total_value);

        return $total_value;
    }

    private function calculateDiscount($item_details, $item_value) {
        $discount_value = 0;
        $discount = (float) $item_details['discount'];
        $discount_type = $item_details['discount_type'];

        if ($discount_type == 0) {
            $discount_value = round(($discount / 100) * $item_value, 2);
        }
        else {
            $discount_value = $discount;
        }

        return $discount_value;
    }

    private function saveOrderDetail($order_id, $item, $notes, $quantity, $item_value, $discount_value, $total_value) {
        $details = new OrderDetail();
        $details->order_id = $order_id;
        $details->item_id = $item['item_id'];
        $details->notes = $notes;
        $details->quantity = $quantity;
        $details->item_value = $item_value;
        $details->discount = $discount_value;
        $details->total_value = $total_value;
        $details->save();
    }

    private function calculateOrderTotal($order_value) {
        $total_order_value = $order_value;
        $vat_value = $this->calculateVat($total_order_value);
        $final_value = $total_order_value + $vat_value;
        $service_charges = $this->calculateServiceCharges($total_order_value);

        return $final_value + $service_charges;
    }

    private function calculateVat($total_order_value) {
        $vat_include = get_vat_value();
        if ($vat_include > 0) {
            return round(($vat_include / 100) * $total_order_value, 2);
        }
        return 0;
    }

    private function calculateServiceCharges($total_order_value) {
        $service_charges_include = get_vat_value();
        if ($service_charges_include > 0) {
            return round(($service_charges_include / 100) * $total_order_value, 2);
        }
        return 0;
    }

    private function updateOrder($order, $order_no, $order_value, $final_value, $startDate, $status) {
        $order->order_no = $order_no;
        $order->order_value = $order_value;
        $order->total_value = $order_value;
        $order->final_value = $final_value;
        $order->status = $status;
        $order->created_at = $startDate->toDateTimeString();
        $order->save();
    }
}
