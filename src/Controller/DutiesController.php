<?php

namespace Perfumerlabs\Start\Controller;

use App\Model\UserQuery;
use Perfumer\Framework\Controller\ViewController;
use Perfumer\Framework\Router\Http\DefaultRouterControllerHelpers;
use Perfumer\Framework\View\StatusView;
use Perfumer\Framework\View\StatusViewControllerHelpers;
use Perfumerlabs\Start\Model\DutyQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class DutiesController extends ViewController
{
    use DefaultRouterControllerHelpers;
    use StatusViewControllerHelpers;

    public function get()
    {
        $duties = DutyQuery::create()
            ->joinWith('Activity')
            ->_if($_id = $this->f('_id'))
                ->filterById((int) $_id)
            ->_endif()
            ->filterByUserId((int) $this->getAuth()->getData())
            ->filterByClosedAt(null, Criteria::ISNULL)
            ->filterByPickedAt(null, Criteria::ISNOTNULL)
            ->useActivityQuery()
                ->orderByPriority(Criteria::DESC)
            ->endUse()
            ->find();

        $content = [];

        $user = UserQuery::create()->findPk((int) $this->getAuth()->getData());

        foreach ($duties as $duty) {
            $content[] = $this->s('perfumerlabs.duty_formatter')->format($duty, $user);
        }

        $this->setContent($content);
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
