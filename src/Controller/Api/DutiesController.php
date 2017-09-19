<?php

namespace Perfumerlabs\Start\Controller\Api;

use Perfumerlabs\Start\Model\DutyQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class DutiesController extends LayoutController
{
    public function get()
    {
        $limit = 30;
        $page = $this->f('page', 1);
        $activity_id = (int) $this->f('activity_id');
        $user_id = (int) $this->f('user_id');

        if ($page < 1) {
            $page = 1;
        }

        $duties = DutyQuery::create()
            ->joinWithActivity()
            ->joinWithUser(Criteria::LEFT_JOIN)
            ->_if($activity_id)
                ->filterByActivityId($activity_id)
            ->_endif()
            ->_if($user_id)
                ->filterByUserId($user_id)
            ->_endif()
            ->filterByClosedAt(null, Criteria::ISNULL)
            ->orderByRaisedAt(Criteria::DESC)
            ->limit($limit)
            ->offset($limit * ($page - 1))
            ->find();

        $content = [];

        foreach ($duties as $duty) {
            $activity = $duty->getActivity();
            $user = $duty->getUser();

            if ($user) {
                $user_content = [
                    'id' => $user->getId(),
                    'username' => $user->getId()
                ];
            } else {
                $user_content = null;
            }

            $content[] = [
                'id' => $duty->getId(),
                'description' => $duty->getDescription(),
                'raised_at' => $duty->getRaisedAt() ? $duty->getRaisedAt('Y-m-d H:i:s') : null,
                'closed_at' => $duty->getClosedAt() ? $duty->getClosedAt('Y-m-d H:i:s') : null,
                'picked_at' => $duty->getPickedAt() ? $duty->getPickedAt('Y-m-d H:i:s') : null,
                'activity' => [
                    'id' => $activity->getId(),
                    'name' => $activity->getName(),
                    'iframe' => $activity->getIframe(),
                    'priority' => $activity->getPriority(),
                    'color' => $activity->getColor(),
                    'postponing' => $activity->getPostponable(),
                    'closing' => $activity->getReadonly(),
                    'commenting' => $activity->getWritable(),
                ],
                'user' => $user_content
            ];
        }

        $this->setContent($content);
    }
}
