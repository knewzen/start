<?php

namespace Perfumerlabs\Start\Controller\Api;

use App\Model\RoleQuery;
use Perfumerlabs\Start\Model\ActivityAccessQuery;
use Perfumerlabs\Start\Model\NavAccessQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class RolesController extends LayoutController
{
    public function get()
    {
        $roles = RoleQuery::create()
            ->orderByName()
            ->find();

        $content = [];

        foreach ($roles as $role) {
            $activities = ActivityAccessQuery::create()
                ->filterByRole($role)
                ->filterByActivityId(null, Criteria::ISNOTNULL)
                ->select('activity_id')
                ->find()
                ->getData();

            $navs = NavAccessQuery::create()
                ->filterByRole($role)
                ->filterByNavId(null, Criteria::ISNOTNULL)
                ->select('nav_id')
                ->find()
                ->getData();

            $content[] = [
                'id' => $role->getId(),
                'name' => $role->getName(),
                'activities' => $activities,
                'navs' => $navs,
            ];
        }

        $this->setContent($content);
    }
}
