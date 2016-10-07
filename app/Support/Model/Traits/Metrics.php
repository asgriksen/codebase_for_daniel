<?php
namespace App\Support\Model\Traits;

trait Metrics {

    /**
     * Return the metric fields.
     *
     * @return array
     */
    public function getMetricFieldsAttribute()
    {
        return $this->attributes['metric_fields'];
    }

    /**
     * Return the metric start date.
     *
     * @return string
     */
    public function getMetricStartDateAttribute()
    {
        return $this->attributes['metric_start_date'];
    }

    /**
     * Return the metric end date.
     *
     * @return string
     */
    public function getMetricEndDateAttribute()
    {
        return $this->attributes['metric_end_date'];
    }

    /**
     * Return the metric time increment
     *
     * @return bool|int|string
     */
    public function getMetricTimeIncrementAttribute()
    {
        return $this->attributes['metric_time_increment'];
    }

    /**
     * Override the metric fields attribute value.
     *
     * @param array $fields
     */
    public function setMetricFieldsAttribute( $fields = [] )
    {
        $this->attributes['metric_fields'] = $fields;
    }

    /**
     * Override the start date attribute value.
     *
     * @param $date
     */
    public function setMetricStartDateAttribute( $date )
    {
        $this->attributes['metric_start_date'] = $date;
    }

    /**
     * Override the end date attribute value.
     *
     * @param $date
     */
    public function setMetricEndDateAttribute( $date )
    {
        $this->attributes['metric_end_date'] = $date;
    }

    /**
     * Override the time increment attribute value.
     *
     * @param $time_increment
     */
    public function setMetricTimeIncrementAttribute( $time_increment )
    {
        $this->attributes['metric_time_increment'] = $time_increment;
    }
}