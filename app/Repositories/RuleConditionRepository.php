<?php

namespace App\Repositories;

use App\Contracts\Repositories\RuleConditionRepository as Contract;
use App\Rule;
use App\RuleCondition;
use App\Support\Repository\Traits\Repositories;

class RuleConditionRepository implements Contract
{
    use Repositories;

    /**
     * @var RuleCondition
     */
    private $model;

    /**
     * @param RuleCondition $ruleCondition
     */
    public function __construct( RuleCondition $ruleCondition )
    {
        $this->model = $ruleCondition;
    }

    /**
     * Attach conditions to the rule.
     *
     * @param array $conditions
     * @param Rule  $rule
     *
     * @return bool
     */
    public function attachToRule( $conditions = [], Rule $rule )
    {
        foreach( $conditions as $condition )
        {
            $rule->conditions()->attach(
                $condition['id'], [
                    'comparable' => $condition['comparable'],
                    'comparison' => $condition['comparison']
                ]
            );
        }

        return true;
    }

    /**
     * Flush out existing attached conditions and then re-attach the ones we want to keep.
     *
     * @param array $conditions
     * @param Rule  $rule
     *
     * @return mixed
     */
    public function flushAndReAttachToRule( $conditions = [], Rule $rule )
    {
        $rule->conditions()->detach();

        return $this->attachToRule( $conditions, $rule );
    }
}