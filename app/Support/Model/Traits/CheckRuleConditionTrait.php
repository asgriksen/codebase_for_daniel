<?php

namespace App\Support\Model\Traits;

trait CheckRuleConditionTrait
{
    /**
     * Determine if the value is a match against the compared comparable.
     *
     * @param $value
     * @param $comparison
     * @param $comparable
     *
     * @return bool
     * @throws \Exception
     */
    public function match($value, $comparison, $comparable)
    {
        return do_comparison($value, $comparison, $comparable);
    }


    public function FacebookStatistics( $interval, $comparison , $comparable )
    {
        // connect to facebook and access the data transfer object
        // $ad_group = $this->getDataTransferObject();

        // What statistics are we pulling?
        // What date range are we to work with?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function AdSet( $interval, $comparison , $comparable )
    {
        // connect to facebook and access the data transfer object
        // $ad_group = $this->getDataTransferObject();

        // What info of the AdSet are we checking?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function CampaignGroup( $interval, $comparison , $comparable )
    {
        // connect to facebook and access the data transfer object
        // $ad_group = $this->getDataTransferObject();

        // What info of the campaign are we checking?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    /**
     * Determine if the number of clicks match the a projected comparison and
     * comparable within a provided interval.
     *
     * @param $interval
     * @param $comparison
     * @param $comparable
     *
     * @return bool
     * @throws \Exception
     */
    public function Clicks( $interval, $comparison , $comparable )
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Capture the number of clicks.
        $clicks = $dto->clicksByCondition( $interval );

        // Check the number of clicks against provided comparison and comparable.
        return $this->match($clicks, $comparison, $comparable);
    }

    public function ConversionRate( $interval, $comparison , $comparable )
    {
        // Is the conversion rate data coming form Facebook or Viral Style?
        // Is there a specific date range we need to check within?


        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function COS( $interval, $comparison , $comparable )
    {
        // Forgive my ignorance, but what is COS? Content Optimization System?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    /**
     * Determine if the cost per click matches the a projected comparison and
     * comparable within a provided interval.
     *
     * @param $interval
     * @param $comparison
     * @param $comparable
     *
     * @return bool
     * @throws \Exception
     */
    public function CPC( $interval, $comparison , $comparable )
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Capture the cost per click.
        $cpc = $dto->cpcByCondition( $interval );

        // Check the number of clicks against provided comparison and comparable.
        return $this->match($cpc, $comparison, $comparable);
    }

    public function CPT( $interval, $comparison , $comparable )
    {
        // Where is this data coming from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    /**
     * Determine if the click through ratio matches the a projected comparison and
     * comparable within a provided interval.
     *
     * @param $interval
     * @param $comparison
     * @param $comparable
     *
     * @return bool
     * @throws \Exception
     */
    public function CTR( $interval, $comparison , $comparable )
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Capture the click through ratio count.
        $ctr = $dto->ctrByCondition( $interval );

        // Check the number of clicks against provided comparison and comparable.
        return $this->match($ctr, $comparison, $comparable);
    }

    public function CurrentBid( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function CurrentBidCPC( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function CurrentBidCPM( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function CurrentBidCPA( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function CurrentState( $interval, $comparison , $comparable )
    {
        // Is this referring to the 'active' state?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    /**
     * Determine if the frequency matches the a projected comparison and
     * comparable within a provided interval.
     *
     * @param $interval
     * @param $comparison
     * @param $comparable
     *
     * @return bool
     * @throws \Exception
     */
    public function Frequency( $interval, $comparison , $comparable )
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Capture the frequency.
        $frequency = $dto->frequencyByCondition( $interval );

        // Check the number of clicks against provided comparison and comparable.
        return $this->match($frequency, $comparison, $comparable);
    }

    /**
     * Determine if the number of impressions match the a projected comparison and
     * comparable within a provided interval.
     *
     * @param $interval
     * @param $comparison
     * @param $comparable
     *
     * @return bool
     * @throws \Exception
     */
    public function Impressions( $interval, $comparison , $comparable )
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Capture the number of impressions.
        $impressions = $dto->impressionsByCondition( $interval );

        // Check the number of clicks against provided comparison and comparable.
        return $this->match($impressions, $comparison, $comparable);
    }

    public function Name( $interval, $comparison , $comparable )
    {
        // What's the intended use of this?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function OCPMClick( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function OCPMActions( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function OCPMReach( $interval, $comparison , $comparable )
    {
        // Is this coming from the 'reach' insight?
        // If so what date range are we to work with?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function OCPMSocial( $interval, $comparison , $comparable )
    {
        // Is this coming from the 'social' insight?
        // If so what date range are we to work with?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function Placement( $interval, $comparison , $comparable )
    {
        // What date range are we to work with?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function Revenue( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function ROI( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function Spent( $interval, $comparison , $comparable )
    {
        // Is this data coming from facebook

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }

    public function Transactions( $interval, $comparison , $comparable )
    {
        // Where do I get this data from?

        return __METHOD__ ."($interval, $comparison, $comparable);";
    }
}