<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;

class StoreAdWizardRequest extends Request
{
  public $body = null;
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return Auth::check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      //
    ];
  }

  public function body() {
    if($this->body == null) {
      $this->body = json_decode(json_encode($this->all()));
    }
    return $this->body;
  }

  /**
   * Get campaign data for Facebook
   *
   * @return array
   */
  public function campaignFBData() {
    $data = [
      CampaignFields::NAME => $this->body()->campaign_data->campaign_name,
      // 'status' => $this->body()->campaign_data->schedule->type == \App\Campaign::SCHEDULE_TYPE_CONTINUE ? \App\Campaign::STATUS_ACTIVE : \App\Campaign::STATUS_PAUSED
    ];

    if($this->body()->campaign_data->schedule->type == \App\Campaign::SCHEDULE_TYPE_START_END) {
      $data[CampaignFields::START_TIME] = $this->body()->campaign_data->schedule->start_date;
      $data[CampaignFields::STOP_TIME] = $this->body()->campaign_data->schedule->end_date;
    }

    return $data;
  }

  /**
   * Get campaign data
   *
   * @return array
   */
  public function campaignData() {
    return [
      'name' => $this->body()->campaign_data->campaign_name,
      'bidding' => $this->body()->campaign_data->bidding * 100,
      'campaign_end' => $this->body()->campaign_data->campaign_end,
      'conversion_pixel' => $this->body()->campaign_data->conversion_pixel,
      // 'status' => $this->body()->campaign_data->schedule->type == \App\Campaign::SCHEDULE_TYPE_CONTINUE ? \App\Campaign::STATUS_ACTIVE : \App\Campaign::STATUS_PAUSED
      'status' => \App\Campaign::STATUS_ACTIVE
    ];
  }

  /**
   * Get adset data for Facebook
   *
   * @param integer $fbCampaignId - facebook campaign id
   * @return array
   */
  public function adSetFBData( $fbCampaignId ) {
    $data = [
      AdSetFields::NAME => $this->body()->campaign_data->adset_name,
      AdSetFields::BID_AMOUNT => $this->body()->campaign_data->bidding * 100,
      AdSetFields::OPTIMIZATION_GOAL => $this->body()->campaign_data->optimize_for,
      AdSetFields::BILLING_EVENT => 'LINK_CLICKS',
      AdSetFields::CAMPAIGN_ID => $fbCampaignId,
    ];

    if($this->body()->campaign_data->schedule->type == \App\Campaign::SCHEDULE_TYPE_START_END) {
      $data[AdSetFields::START_TIME] = $this->body()->campaign_data->schedule->start_date;
      $data[AdSetFields::END_TIME] = $this->body()->campaign_data->schedule->end_date;
    }

    if($this->body()->campaign_data->adset_budget_type == 'DAILY') {
      $data[AdSetFields::DAILY_BUDGET] = $this->body()->campaign_data->adset_budget * 100;
    }
    else if($this->body()->campaign_data->adset_budget_type == 'LIFETIME') {
      $data[AdSetFields::LIFETIME_BUDGET] = $this->body()->campaign_data->adset_budget * 100;
    }

    $data[AdSetFields::ADSET_SCHEDULE] = [];
    if($this->body()->campaign_data->schedule->type == \App\Campaign::SCHEDULE_TYPE_DAYS_OF_WEEK) {
      foreach($this->body()->campaign_data->schedule->days_of_week as $days) {
        $data[AdSetFields::ADSET_SCHEDULE][] = [
          'days' => [ (int)$days->day ],
          'start_minute' => $this->__convertHoursToMins( $days->start ),
          'end_minute' => $this->__convertHoursToMins( $days->end )
        ];
      }
    }

    return $data;
  }

  private function __convertHoursToMins($hour) {
    $hour = strtolower($hour);
    $tmp = explode(':', $hour);
    $h = $tmp[0];
    $m = $tmp[1];
    if(substr($hour, -2) == 'pm') {
      $h += 12;
    }

    return (int)($h * 60 + $m);
  }


  public function adSetFBTargetingData() {
    $targeting = [];

    $targeting_data = $this->body()->targeting_data;

    $targeting['geo_locations'] = [ 
      'countries' => [],
      'regions' => [],
      'cities' => [],
      'zips' => [],
      'custom_locations' => [],
      'geo_markets' => [],
      'location_types' => []
    ];
    foreach($targeting_data->locations->includes as $location) {
      if(!isset($location->type)) continue;
      switch(true) {
        case $location->type == 'country': {
          $targeting['geo_locations']['countries'][] = $location->country_code;
          break;
        }
        case $location->type == 'region': {
          $targeting['geo_locations']['regions'][] = [ 'key' => $location->key ];
          break;
        }
        case $location->type == 'city': {
          $targeting['geo_locations']['cities'][] = ['key' => $location->key, 'radius' => $location->radius, 'distance_unit' => 'mile'];
          break;
        }
        case $location->type == 'zip': {
          $targeting['geo_locations']['zips'][] = ['key' => $location->key];
          break;
        }
      }
    }


    $targeting['excluded_geo_locations'] = [ 
      'countries' => [],
      'regions' => [],
      'cities' => [],
      'zips' => [],
      'custom_locations' => [],
      'geo_markets' => [],
      'location_types' => []
    ];
    foreach($targeting_data->locations->excludes as $location) {
      if(!isset($location->type)) continue;
      switch(true) {
        case $location->type == 'country': {
          $targeting['excluded_geo_locations']['countries'][] = $location->country_code;
          break;
        }
        case $location->type == 'region': {
          $targeting['excluded_geo_locations']['regions'][] = [ 'key' => $location->key ];
          break;
        }
        case $location->type == 'city': {
          $targeting['excluded_geo_locations']['cities'][] = ['key' => $location->key, 'radius' => $location->radius, 'distance_unit' => 'mile'];
          break;
        }
        case $location->type == 'zip': {
          $targeting['excluded_geo_locations']['zips'][] = ['key' => $location->key];
          break;
        }
      }
    }

    if($targeting_data->gender == 'ALL') {

    }
    else if($targeting_data->gender == 'MEN') {
      $targeting['genders'] = [1];
    }
    else if($targeting_data->gender == 'WOMEN') {
      $targeting['genders'] = [2];
    }

    if(isset($targeting_data->age)) {
      if(isset($targeting_data->age->from)) $targeting['age_min'] = $targeting_data->age->from;
      if(isset($targeting_data->age->to) && $targeting_data->age->to != '60+') $targeting['age_max'] = $targeting_data->age->to;
    }

    $targeting['interests'] = [];
    foreach($targeting_data->interests as $interest) {
      $targeting['interests'][] = ['id' => $interest->id, 'name' => $interest->name ];
    }

    $targeting['behaviors'] = [];
    foreach($targeting_data->behaviors as $behavior) {
      $targeting['behaviors'][] = ['id' => $behavior->id, 'name' => $behavior->name ];
    }

    if($targeting_data->connections->type == 'ADV') {
      $targeting['connections']  = [];
      foreach($targeting_data->connections->includes as $connection) {
        $targeting['connections'][] = ['id' => $connection->id, 'name' => $connection->name];
      }
      $targeting['excluded_connections']  = [];
      foreach($targeting_data->connections->excludes as $connection) {
        $targeting['excluded_connections'][] = ['id' => $connection->id, 'name' => $connection->name];
      }
      $targeting['friends_of_connections']  = [];
      foreach($targeting_data->connections->friends as $connection) {
        $targeting['friends_of_connections'][] = ['id' => $connection->id, 'name' => $connection->name];
      }
    }

    return $targeting;
  }


  /**
   * Get adset data
   *
   * @return array
   */
  public function adSetData() {
    $data = [
      'name' => $this->body()->campaign_data->adset_name,
      'type' => isset($this->body()->type) ? $this->body()->type : 'GET_VISITORS',
      'prefix' => $this->body()->campaign_data->adset_prefix,
      'budget_remaining' => 0,
      // 'status' => $this->body()->campaign_data->schedule->type == \App\Campaign::SCHEDULE_TYPE_CONTINUE ? \App\AdSet::STATUS_ACTIVE : \App\AdSet::STATUS_PAUSED,
      'status' => \App\AdSet::STATUS_ACTIVE,
    ];

    if($this->body()->campaign_data->adset_budget_type == 'DAILY') {
      $data['daily_budget'] = $this->body()->campaign_data->adset_budget * 100;
      $data['lifetime_budget'] = 0;
    }
    else if($this->body()->campaign_data->adset_budget_type == 'LIFETIME') {
      $data['daily_budget'] = 0;
      $data['lifetime_budget'] = $this->body()->campaign_data->adset_budget * 100;
    }

    return $data;
  }
}
