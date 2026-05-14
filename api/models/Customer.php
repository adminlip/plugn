<?php

namespace api\models;

use Yii;
use yii\filters\RateLimitInterface;

class Customer extends \common\models\Customer implements RateLimitInterface
{
    public function fields()
    {
        $fields = parent::fields();

        unset($fields['customer_password_hash'],
            $fields['customer_password_reset_token'],
            $fields['restaurant_uuid'],
            $fields['deleted'],
        );

        return $fields;
    }

    /**
     * @inheritdoc
     * Returns the maximum number of allowed requests and the time period.
     * [max requests, time window in seconds]
     */
    public function getRateLimit($request, $action)
    {
        return [60, 60]; // 60 requests per 60 seconds
    }

    /**
     * @inheritdoc
     * Loads the number of allowed requests and the corresponding timestamp from cache.
     */
    public function loadAllowance($request, $action)
    {
        $cache = Yii::$app->cache;
        $key = 'rate_limit_' . $this->customer_id . '_' . $action->uniqueId;
        $data = $cache->get($key);

        if ($data === false) {
            return [0, 0];
        }

        return $data;
    }

    /**
     * @inheritdoc
     * Saves the number of allowed requests and the corresponding timestamp to cache.
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $cache = Yii::$app->cache;
        $key = 'rate_limit_' . $this->customer_id . '_' . $action->uniqueId;
        $cache->set($key, [$allowance, $timestamp], 60);
    }
}