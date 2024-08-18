<?php

namespace App\Providers;

use App\Brokers\Kafka\Consumer\ConsumerMessage;
use App\Brokers\Kafka\Facades\Consumer;
use App\Brokers\TopicNameEnum;
use App\Impl\ConditionHandlerFactory;
use App\Impl\DbRepository;
use App\Impl\FreezeSmartLinkService;
use App\Impl\MacroCommand;
use App\Impl\SmartLink;
use App\Impl\SmartLinkRedirectRulesRepository;
use App\Impl\SmartLinkRedirectService;
use App\Impl\StatableSmartLinkRepository;
use App\Impl\SupportedHttpRequest;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use App\Interfaces\Broker\ConsumerInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\FreezeSmartLinkServiceInterface;
use App\Interfaces\RedirectRuleInterface;
use App\Interfaces\RuleConditionInterface;
use App\Interfaces\SmartLinkInterface;
use App\Interfaces\SmartLinkRedirectRulesRepositoryInterface;
use App\Interfaces\SmartLinkRedirectServiceInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SupportedHttpRequestInterface::class, SupportedHttpRequest::class);
        $this->app->bind(SmartLinkInterface::class, SmartLink::class);
        $this->app->bind(DbRepositoryInterface::class, DbRepository::class);
        $this->app->bind(StatableSmartLinkRepositoryInterface::class, StatableSmartLinkRepository::class);
        $this->app->bind(FreezeSmartLinkServiceInterface::class, FreezeSmartLinkService::class);
        $this->app->bind(SmartLinkRedirectServiceInterface::class, SmartLinkRedirectService::class);
        $this->app->bind(SmartLinkRedirectRulesRepositoryInterface::class, SmartLinkRedirectRulesRepository::class);
        $this->app->bind(ConditionHandlerFactoryInterface::class, ConditionHandlerFactory::class);
        $this->app->bind(Collection::class, function () {
            return \App\Models\SmartLink::query()->get();
        });

        $this->app->bind(MacroCommand::class, function ($app, array $params) {
            return new MacroCommand(...$params);
        });

        $this->app->bind('Namespace.ConditionHandlers', function () {
            return "\\App\\Impl\\ConditionHandlers\\";
        });

        $this->app->bind(RedirectRuleInterface::class, function ($app, array $params) {
            return new \App\Impl\RedirectRule(...$params);
        });
        $this->app->bind(RuleConditionInterface::class, function ($app, array $params) {
            return new \App\Impl\RuleCondition(...$params);
        });
        $this->app->bind(ConditionTypeInterface::class, function ($app, array $params) {
            return new \App\Impl\ConditionType(...$params);
        });

        //Broker
        $this->app->bind(ConsumerInterface::class, Consumer::class);
        $this->app->bind(ConsumerMessageInterface::class, ConsumerMessage::class);

        $this->app->bind('Broker.TopicNames', function () {
            return array_map(fn(\UnitEnum $enum) => $enum->name, TopicNameEnum::cases());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
