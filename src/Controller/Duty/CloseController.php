<?php

namespace Perfumerlabs\Start\Controller\Duty;

use Perfumer\Framework\Controller\PlainController;
use Perfumer\Framework\Router\Http\DefaultRouterControllerHelpers;
use Perfumer\Framework\View\StatusView;
use Perfumer\Framework\View\StatusViewControllerHelpers;
use Perfumerlabs\Start\Model\DutyQuery;

class CloseController extends PlainController
{
    use DefaultRouterControllerHelpers;
    use StatusViewControllerHelpers;

    public function post()
    {
        $id = (int) $this->i();

        $duty = DutyQuery::create()->findPk($id);

        if ($duty) {
            $this->s('perfumerlabs.duty')->close($duty);
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