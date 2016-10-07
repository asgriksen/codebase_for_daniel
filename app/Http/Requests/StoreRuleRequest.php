<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\ConditionRepository;
use App\Contracts\Repositories\RuleRepository;
use App\Http\Requests\Request;
use Auth;

class StoreRuleRequest extends Request
{
    /**
     * @var RuleRepository
     */
    private $ruleRepository;

    /**
     * @var ConditionRepository
     */
    private $conditionRepository;

    /**
     * Create new StoreRuleRequest instance.
     *
     * @param RuleRepository      $ruleRepository
     * @param ConditionRepository $conditionRepository
     */
    public function __construct(
        RuleRepository $ruleRepository,
        ConditionRepository $conditionRepository
    ){
        $this->ruleRepository = $ruleRepository;
        $this->conditionRepository = $conditionRepository;
    }

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
        $intervals  = $this->intervalsAsString();
        $layers     = $this->layersAsString();
        $strategies = $this->strategiesAsString();

        $rules = [
            'interval'        => 'required|in:'.$intervals,
            'name'            => 'required',
            'layer'           => 'required|in:'.$layers,
            'report_repeated' => 'required',
            'report_email'    => 'required|email',
            'strategy'        => 'required|in:'.$strategies,
            'conditions'      => 'required|array',
            'actions'         => 'required|array',
        ];

        if( is_array($this->actions) )
        {
            foreach( $this->actions as $key => $value )
            {
                $rules["actions.$key"] = "integer|exists:actions,id";
            }
        }

        if( is_array($this->conditions) )
        {
            foreach( $this->conditions as $key => $value )
            {
                $comparison_options = $this->comparisonOptionsAsString( $this->conditions[$key]['id'] );

                $rules["conditions.$key.id"]         = "integer|exists:conditions,id";
                $rules["conditions.$key.comparable"] = "integer|exists:conditions,id";
                $rules["conditions.$key.comparison"] = "required|in:$comparison_options";
            }
        }

        return $rules;
    }

    /**
     * Return the intervals list as a comma delimited string.
     *
     * @return string
     */
    public function intervalsAsString()
    {
        return implode(',', $this->ruleRepository->getIntervalsList());
    }

    /**
     * Return the layers list as a comma delimited string.
     *
     * @return string
     */
    public function layersAsString()
    {
        return implode(',', $this->ruleRepository->getLayersList());
    }

    /**
     * Take the strategies array and convert it to a string format
     * that can be used appropriately by the validator.
     *
     * @return string
     */
    private function strategiesAsString()
    {
        return implode(',', $this->ruleRepository->getStrategiesList());
    }

    /**
     * Take the comparison options array and convert it to a string format
     * that can be used appropriately by the validator.
     *
     * @param $id
     *
     * @return string
     */
    private function comparisonOptionsAsString( $id )
    {
        return implode(',', $this->getComparisonOptionsById($id));
    }

    /**
     * Return the comparison options for a given condition.
     *
     * @param $id
     *
     * @return array
     */
    public function getComparisonOptionsById( $id )
    {
        $record = $this->conditionRepository->byId( $id );

        if( ! $record )
        {
            return [];
        }

        return $record->comparison_options;
    }
}
