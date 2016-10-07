<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Queue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * List of contracts that will need to be
     * bound to their implementation.
     *
     * @var array
     */
    protected $contracts_to_bind = [
        'Repositories\\AccountRepository',
        'Repositories\\AdRepository',
        'Repositories\\AdCreativeRepository',
        'Repositories\\AdSetRepository',
        'Repositories\\CampaignRepository',
        'Repositories\\CartRepository',
        'Repositories\\CartCategoryRepository',
        'Repositories\\ConditionRepository',
        'Repositories\\EtsyRequestTokenRepository',
        'Repositories\\GoogleAccessTokenRepository',
        'Repositories\\GoogleAnalyticAccountRepository',
        'Repositories\\NotificationRepository',
        'Repositories\\ProductRepository',
        'Repositories\\ProductImageRepository',
        'Repositories\\RuleRepository',
        'Repositories\\RuleActionRepository',
        'Repositories\\RuleApplicationRepository',
        'Repositories\\RuleConditionRepository',
        'Repositories\\TaskRepository',
        'Repositories\\UserPreferenceRepository',
        'Repositories\\UserRepository',
        'Repositories\\UtmCodeRepository',
    ];

    /**
     * List of models that will need to be observed.
     *
     * @var array
     */
    protected $models_to_observe = [
        'Account',
        'AdSet',
        'Campaign',
        'Cart',
        'EtsyRequestToken',
        'GoogleAccessToken',
        'Product',
        'Rule',
        'User'
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindContractsToImplementations();

        $this->extendValidator();

        $this->handleQueueFailures();

        $this->observeModels();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \Illuminate\Contracts\Auth\Registrar::class,
            \App\Services\Registrar::class
        );

        $this->app->register(\Darkaonline\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bind contracts to their implementation.
     *
     * @return void
     */
    public function bindContractsToImplementations()
    {
        foreach( $this->contracts_to_bind as $bindable )
        {
            $contract        = "\\App\\Contracts\\{$bindable}";
            $implementation  = "\\App\\{$bindable}";

            app()->bind( $contract , $implementation );
        }
    }

    /**
     * Extend Laravel's Validator Class
     *
     * @return void
     */
    public function extendValidator()
    {
        Validator::extend(
            'exists_polymorphically', '\App\Http\Validators\ExistsPolymorphicallyValidator@validate'
        );
    }

    /**
     * Handle when there is a failure within the queue.
     *
     * @return void
     */
    public function handleQueueFailures()
    {
        Queue::failing(function($connection, $job, $data)
        {
            // @todo - Notify team of failing job...
        });
    }

    /**
     * Assign observer to model.
     *
     * @return void
     */
    public function observeModels()
    {
        foreach( $this->models_to_observe as $model )
        {
            $eloquentModel = "\\App\\{$model}";
            $oberverClass  = "\\App\\Observers\\{$model}Observer";

            $eloquentModel::observe( $oberverClass );
        }
    }
}
