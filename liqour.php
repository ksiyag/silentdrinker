<?php

/**
 * onlineLiquorDelivery
 *
 * This function provides an online liquor delivery service.
 *
 * @param array $order An array containing the order details
 * @param array $inventory An array containing the inventory details
 *
 * @return array An array containing the order details and delivery status
 */
function onlineLiquorDelivery($order, $inventory) {
    // Initialize the output array
    $output = array();
    $output['order'] = $order;
    $output['delivery_status'] = 'pending';

    // Validate the order
    if (!validateOrder($order)) {
        $output['delivery_status'] = 'invalid';
        return $output;
    }

    // Validate the inventory
    if (!validateInventory($inventory)) {
        $output['delivery_status'] = 'invalid';
        return $output;
    }

    // Check if the order can be fulfilled
    if (!checkInventory($order, $inventory)) {
        $output['delivery_status'] = 'unfulfilled';
        return $output;
    }

    // Update the inventory
    $inventory = updateInventory($order, $inventory);

    // Set the delivery status to fulfilled
    $output['delivery_status'] = 'fulfilled';

    // Log the order
    logOrder($order);

    return $output;
}

/**
 * validateOrder
 *
 * This function validates an order.
 *
 * @param array $order An array containing the order details
 *
 * @return bool True if the order is valid, false otherwise
 */
function validateOrder($order) {
    // Check if all the required fields are present
    if (!isset($order['customer_name']) || !isset($order['items']) || !isset($order['total_price'])) {
        return false;
    }

    // Check if the items are valid
    foreach ($order['items'] as $item) {
        if (!isset($item['name']) || !isset($item['quantity']) || !isset($item['price'])) {
            return false;
        }
    }

    return true;
}

/**
 * validateInventory
 *
 * This function validates an inventory.
 *
 * @param array $inventory An array containing the inventory details
 *
 * @return bool True if the inventory is valid, false otherwise
 */
function validateInventory($inventory) {
    // Check if all the required fields are present
    if (!isset($inventory['items']) || !isset($inventory['total_price'])) {
        return false;
    }

    // Check if the items are valid
    foreach ($inventory['items'] as $item) {
        if (!isset($item['name']) || !isset($item['quantity']) || !isset($item['price'])) {
            return false;
        }
    }

    return true;
}

/**
 * checkInventory
 *
 * This function checks if an order can be fulfilled.
 *
 * @param array $order An array containing the order details
 * @param array $inventory An array containing the inventory details
 *
 * @return bool True if the order can be fulfilled, false otherwise
 */
function checkInventory($order, $inventory) {
    // Check if the order items are in the inventory
    foreach ($order['items'] as $orderItem) {
        $found = false;
        foreach ($inventory['items'] as $inventoryItem) {
            if ($orderItem['name'] == $inventoryItem['name'] && $orderItem['quantity'] <= $inventoryItem['quantity']) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            return false;
        }
    }

    // Check if the total price of the order is less than or equal to the total price of the inventory
    if ($order['total_price'] > $inventory['total_price']) {
        return false;
    }

    return true;
}

/**
 * updateInventory
 *
 * This function updates the inventory.
 *
 * @param array $order An array containing the order details
 * @param array $inventory An array containing the inventory details
 *
 * @return array An array containing the updated inventory
 */
function updateInventory($order, $inventory) {
    // Update the inventory items
    foreach ($order['items'] as $orderItem) {
        foreach ($inventory['items'] as &$inventoryItem) {
            if ($orderItem['name'] == $inventoryItem['name']) {
                $inventoryItem['quantity'] -= $orderItem['quantity'];
                break;
            }
        }
    }

    // Update the total price of the inventory
    $inventory['total_price'] -= $order['total_price'];

    return $inventory;
}

/**
 * logOrder
 *
 * This function logs an order.
 *
 * @param array $order An array containing the order details
 */
function logOrder($order) {
    // Log the order
    // ...
}

?>