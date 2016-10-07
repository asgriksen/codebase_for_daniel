<?php

namespace App\Repositories;

use App\Contracts\Repositories\RuleActionRepository as Contract;
use App\Rule;
use App\RuleAction;
use App\Support\Repository\Traits\Repositories;

class RuleActionRepository implements Contract
{
    use Repositories;

    /**
     * @var RuleAction
     */
    private $model;

    /**
     * Create new RuleActionRepository instance.
     *
     * @param RuleAction $ruleAction
     */
    public function __construct( RuleAction $ruleAction )
    {
        $this->model = $ruleAction;
    }

    /**
     * Attach actions to the rule.
     *
     * @param array $actions
     * @param Rule  $rule
     */
    public function attachToRule( $actions = [], Rule $rule )
    {
        return $rule->actions()->sync( $actions );
    }

    /**
     * Flush out existing attached actions and then re-attach the ones we want to keep.
     *
     * @param array $actions
     * @param Rule  $rule
     *
     * @return mixed
     */
    public function flushAndReAttachToRule( $actions = [], Rule $rule )
    {
        $rule->actions()->detach();

        return $this->attachToRule( $actions, $rule );
    }
}