<?php

namespace Timedoor\TmdMidtransIris;

use Illuminate\Support\ServiceProvider;
use Timedoor\TmdMidtransIris\Aggregator\BankAccount as AggregatorBankAccount;
use Timedoor\TmdMidtransIris\Aggregator\TopUp;
use Timedoor\TmdMidtransIris\Facilitator\BankAccount as FacilitatorBankAccount;

class IrisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('midtrans.php'),
            ], ['config', 'midtrans-iris']);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'midtrans');

        $this->registerServices();
    }

    /**
     * Bind all services class to the container
     *
     * @return void
     */
    private function registerServices()
    {
        $this->app->bind(Iris::class, function ($app) {
            $config = $app['config']['midtrans']['iris'];

            return new Iris([
                'creator_api_key'   => $config['api_key']['creator'],
                'approver_api_key'  => $config['api_key']['approver'],
                'merchant_key'      => $config['merchant_key'],
            ]);
        });

        $this->registerComponent(BankAccount::class);
        $this->registerComponent(AggregatorBankAccount::class);
        $this->registerComponent(FacilitatorBankAccount::class);

        $this->registerComponent(Beneficiary::class);

        $this->registerComponent(Payout::class);

        $this->registerComponent(Transaction::class);

        $this->registerComponent(TopUp::class);
    }

    /**
     * Register individual service component
     *
     * @param   mixed $component
     * @return  void
     */
    private function registerComponent($component)
    {
        $this->app->bind($component, function ($app) use ($component) {
            return new $component(
                $app[Iris::class]->getApiClient()
            );
        });
    }
}