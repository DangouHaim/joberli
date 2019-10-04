<?php

function isUserHasRateOrder($orderId) {

    $uid = get_current_user_id();

    if($orderId && $uid) {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(id) FROM up_rating WHERE userId = " . $uid . " AND orderId = " . $orderId );
        return $result;
    }
    return -1;

}

function prepareRating($orderId, $rating) {

    $uid = get_current_user_id();
    if(!$uid || !$orderId) {
        return -1;
    }

    global $wpdb;

    $wpdb->insert( 
        'up_rating',
        array(
            'userId' => $uid,
            'orderId' => $orderId,
            'postId' => getPost($orderId),
            'rating' => $rating
        ),
        array( 
            '%s' 
        ) 
    );
    return $wpdb->insert_id;
}

function updateRating($orderId, $rating) {

    $uid = get_current_user_id();
    if(!$uid || !$orderId) {
        return -1;
    }

    global $wpdb;

    $result = $wpdb->update( 
        'up_rating', 
        array( 
            'rating' => $rating
        ), 
        array(
            'userId' => $uid,
            'orderId' => $orderId,
            'postId' => getPost($orderId),
        ),
        array( 
            '%d'
        ), 
        array( '%d' ) 
    );

    return $result;
}

function rateOrder($orderId, $rating) {
    
    $rating = intval($rating);
    $orderId = intval($orderId);
    $uid = get_current_user_id();
    
    if($rating < 0 || $rating > 5) {
        return "Оценка должна быть целочисленным числом от 0 до 5";
    }

    if(!$uid || !$orderId || !isUserOrder($orderId)) {
        return "Ошибка доступа.";
    }

    if(!isOrderDone($orderId)) {
        return "Невозможно оценить незавершённый заказ.";
    }
    
    $result = -1;

    if(!isUserHasRateOrder($orderId)) {
        $result = prepareRating($orderId, $rating);
    } else {
        $result = updateRating($orderId, $rating);
    }

    if($result < 0) {
        return "Ошибка.";
    }

    return $result;
}

function getPostRating($postId) {

    if(!$postId) {
        return "Ошибка доступа.";
    }
    
    $total = 0;

    global $wpdb;

    $count = $wpdb->get_var("SELECT COUNT(id) FROM up_rating WHERE postId = " . $postId );

    $results = $wpdb->get_results("SELECT * FROM up_rating WHERE postId = " . $postId );

    foreach($results as $result) {
        $total += $result->rating;
    }
    
    if($total && $count) {
        return $total / $count;
    }

    return 0;
}
function getProductRating($orderId){
    if(!$orderId) {
        return "Ошибка доступа.";
    }

    global $wpdb;

    $result = $wpdb->get_results("SELECT rating FROM up_rating WHERE orderId = " . $orderId );

    return $result[0]->rating;
}