<?php

namespace Perfumerlabs\Start\Controller;

use App\Model\UserQuery;
use Perfumer\Framework\Controller\ViewController;
use Perfumer\Framework\Router\Http\DefaultRouterControllerHelpers;
use Perfumer\Framework\View\StatusView;
use Perfumer\Framework\View\StatusViewControllerHelpers;
use Perfumerlabs\Start\Model\Duty;
use Perfumerlabs\Start\Model\DutyQuery;
use Perfumerlabs\Start\Model\RelatedTagQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class ExtraController extends ViewController
{
    use StatusViewControllerHelpers;
    use DefaultRouterControllerHelpers;

    public function get()
    {
        $user = UserQuery::create()->findPk((int) $this->getAuth()->getData());

        $user->setOnlineAt(new \DateTime());
        $user->save();

        $picked_duties = DutyQuery::create()
            ->joinWith('Activity')
            ->filterByUserId((int) $this->getAuth()->getData())
            ->filterByClosedAt(null, Criteria::ISNULL)
            ->filterByPickedAt(null, Criteria::ISNOTNULL)
            ->find();

        $have_ids = explode(',', $this->f('have_ids'));
        $missing_duties = [];
        $highest_priority = 0;

        foreach ($picked_duties as $duty) {
            /** @var Duty $duty */
            $duty_priority = $duty->getActivity()->getPriority();

            if ($duty_priority > $highest_priority) {
                $highest_priority = $duty_priority;
            }

            if (!in_array($duty->getId(), $have_ids)) {
                $missing_duties[] = $duty;
            }
        }

        if (count($missing_duties) > 0) {
            $content = [];

            foreach ($missing_duties as $duty) {
                $content[] = $this->s('perfumerlabs.duty_formatter')->format($duty, $user);
            }

            $this->setContentAndExit($content);
        }

        $allowed_activities = $this->s('perfumerlabs.start')->getAllowedActivities($user);

        if (count($allowed_activities) == 0) {
            return;
        }

        $extra_duty = DutyQuery::create()
            ->joinWith('Activity')
            ->condition('user', 'Duty.UserId = ?', (int) $this->getAuth()->getData())
            ->condition('no_user', 'Duty.UserId IS NULL')
            ->condition('allowed', 'Duty.ActivityId IN ?', $allowed_activities)
            ->combine(['no_user', 'allowed'], 'and', 'allowed_and_no_user')
            ->where(['user', 'allowed_and_no_user'], 'or')
            ->filterByClosedAt(null, Criteria::ISNULL)
            ->filterByPickedAt(null, Criteria::ISNULL)
            ->filterByRaisedAt(new \DateTime(), Criteria::LESS_EQUAL)
            ->filterById($picked_duties->getPrimaryKeys(), Criteria::NOT_IN)
            ->useActivityQuery()
                ->filterByPriority($highest_priority, Criteria::GREATER_THAN)
                ->orderByPriority(Criteria::DESC)
            ->endUse()
            ->orderByRaisedAt()
            ->findOne();

        if ($extra_duty) {
            $content = [];

            $extra_duty->setPickedAt(new \DateTime());
            $extra_duty->setUserId((int) $this->getAuth()->getData());

            if ($extra_duty->save()) {
                $content[] = $this->s('perfumerlabs.duty_formatter')->format($extra_duty, $user);
            }

            $related_tag_ids = RelatedTagQuery::create()
                ->filterByDutyId($extra_duty->getId())
                ->select('tag_id')
                ->find()
                ->getData();

            if ($related_tag_ids) {
                $related_duties = DutyQuery::create()
                    ->joinWith('Activity')
                    ->filterByUserId((int) $this->getAuth()->getData())
                    ->_or()
                    ->filterByActivityId($allowed_activities, Criteria::IN)
                    ->filterByClosedAt(null, Criteria::ISNULL)
                    ->filterByPickedAt(null, Criteria::ISNULL)
                    ->filterByRaisedAt(new \DateTime(), Criteria::LESS_EQUAL)
                    ->filterById($picked_duties->getPrimaryKeys(), Criteria::NOT_IN)
                    ->useRelatedTagQuery()
                        ->filterByTagId($related_tag_ids, Criteria::IN)
                    ->endUse()
                    ->find();

                foreach ($related_duties as $related_duty) {
                    $related_duty->setPickedAt(new \DateTime());
                    $related_duty->setUserId((int) $this->getAuth()->getData());

                    if ($related_duty->save()) {
                        $content[] = $this->s('perfumerlabs.duty_formatter')->format($related_duty, $user);
                    }
                }
            }

            $this->setContent($content);
        }
    }

    /**
     * @return StatusView
     */
    protected function getView()
    {
        if ($this->_view === null) {
            $this->_view = $this->s('view.status');
        }

        return $this->_view;
    }
}
