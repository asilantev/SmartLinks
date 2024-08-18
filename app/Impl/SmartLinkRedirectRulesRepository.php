<?php

namespace App\Impl;

use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\RedirectRuleInterface;
use App\Interfaces\SmartLinkRedirectRulesRepositoryInterface;
use Illuminate\Support\Collection;

class SmartLinkRedirectRulesRepository implements SmartLinkRedirectRulesRepositoryInterface
{
    public function __construct(private DbRepositoryInterface $dbRepository)
    {
    }

    public function read(): Collection
    {
        $ruleCollection = new Collection();

        /** @var \App\Models\SmartLink $model */
        $model = $this->dbRepository->read();
        if ($model) {
            $redirectRuleCollection = $model->redirectRules()->orderBy('priority')->with('conditions')->get();
            foreach ($redirectRuleCollection as $redirectRule) {
                if ($redirectRule->is_active) {
                    $ruleCollection->add(app(RedirectRuleInterface::class, [$redirectRule]));
                }
            }
        }

        return $ruleCollection;
    }
}
