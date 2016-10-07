<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\RuleRepository;
use App\Rule;
use App\RuleAction;
use App\RuleCondition;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnforceRules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enforce:rules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enforce rules assigned to campaigns, ad sets, and ads';
    /**
     * @var RuleRepository
     */
    private $ruleRepository;

    /**
     * Create a new command instance.
     *
     * @param RuleRepository $ruleRepository
     */
    public function __construct( RuleRepository $ruleRepository )
    {
        parent::__construct();

        $this->ruleRepository = $ruleRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Iterate through all the rules in the system.
        foreach( $this->getAllRules() as $rule )
        {
            // Go through all the models assigned to the rule.
            foreach( $rule->applications as $application )
            {
                // Define what is the model.
                $model = $application->layer;

                // This how many conditions must be met so that actions can be applied.
                $target_conditions_count = $rule->conditions->count();

                // This will be the running count for matched conditions.
                $counter = 0;

                // Now iterate through each conditions and compare it against the model.
                foreach( $rule->conditions as $condition )
                {
                    // Check the rule against the model.
                    $condition_met = $this->checkRuleConditionAgainstModel( $rule, $condition, $model );

                    // If the condition is met then increment the counter.
                    if( $condition_met ) $counter++;
                }

                // If the count matches the target count then we want to
                // apply all of the actions specified.
                if( $counter == $target_conditions_count )
                {
                    // Iterate through all of the actions.
                    foreach( $rule->actions as $action )
                    {
                        // Now apply the action against model.
                        $this->applyActionAgainstModel( $action, $model );
                    }
                }
            }
        }
    }

    /**
     * Fetch all rules from the database.
     *
     * @return Collection
     */
    public function getAllRules()
    {
        return $this->ruleRepository->allWith([
            'actions',
            'actions.action',
            'conditions',
            'conditions.condition',
            'applications',
            'applications.layer'
        ]);
    }

    /**
     * Apply action against a model.
     *
     * @param RuleAction $action
     * @param Object     $model
     *
     * return mixed
     */
    public function applyActionAgainstModel( RuleAction $action, $model )
    {
        $methodName = Str::studly( $action->action->name );

        return $model->$methodName();
    }

    /**
     * Check if rule and condition match when compared against the model.
     *
     * @param Rule          $rule
     * @param RuleCondition $rule_condition
     * @param mixed         $model
     *
     * @return boolean
     */
    public function checkRuleConditionAgainstModel( Rule $rule, RuleCondition $rule_condition, $model )
    {
        $interval   = $rule->interval;
        $comparison = $rule_condition->comparison;
        $comparable = $rule_condition->comparable;
        $methodName = Str::studly($rule_condition->condition->name);

        return $model->$methodName( $interval, $comparison, $comparable );
    }
}
