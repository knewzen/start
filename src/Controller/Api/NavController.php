<?php

namespace Perfumerlabs\Start\Controller\Api;

use Perfumerlabs\Start\Model\Nav;
use Perfumerlabs\Start\Model\NavQuery;

class NavController extends LayoutController
{
    public function get()
    {
        $id = (int) $this->i();

        if (!$id) {
            $this->getExternalResponse()->setStatusCode(404);
            $this->setStatusAndExit(false);
        }

        $nav = NavQuery::create()->findPk($id);

        if (!$nav) {
            $this->getExternalResponse()->setStatusCode(404);
            $this->setStatusAndExit(false);
        }

        $this->setContent([
            'id' => $nav->getId(),
            'name' => $nav->getName(),
            'activity_id' => $nav->getActivityId(),
            'url' => $nav->getUrl(),
            'priority' => $nav->getPriority()
        ]);
    }

    public function post()
    {
        $fields = $this->f(['name', 'activity_id', 'priority', 'url']);

        $nav = new Nav();
        $nav->setName((string) $fields['name']);
        $nav->setActivityId($fields['activity_id']);
        $nav->setPriority((int) $fields['priority']);
        $nav->setUrl($fields['url']);
        $nav->save();

        $this->getExternalResponse()->setStatusCode(201);

        $this->setContent([
            'id' => $nav->getId(),
            'name' => $nav->getName(),
            'activity_id' => $nav->getActivityId(),
            'url' => $nav->getUrl(),
            'priority' => $nav->getPriority()
        ]);
    }

    public function put()
    {
        $id = (int) $this->i();

        if (!$id) {
            $this->getExternalResponse()->setStatusCode(404);
            $this->setStatusAndExit(false);
        }

        $nav = NavQuery::create()->findPk($id);

        if (!$nav) {
            $this->getExternalResponse()->setStatusCode(404);
            $this->setStatusAndExit(false);
        }

        $fields = $this->f(['name', 'activity_id', 'priority', 'url']);

        $nav->setName((string) $fields['name']);
        $nav->setActivityId($fields['activity_id']);
        $nav->setPriority((int) $fields['priority']);
        $nav->setUrl($fields['url']);
        $nav->save();

        $this->setContent([
            'id' => $nav->getId(),
            'name' => $nav->getName(),
            'activity_id' => $nav->getActivityId(),
            'url' => $nav->getUrl(),
            'priority' => $nav->getPriority()
        ]);
    }

    public function delete()
    {
        $id = (int) $this->i();

        if (!$id) {
            $this->getExternalResponse()->setStatusCode(404);
            $this->setStatusAndExit(false);
        }

        $nav = NavQuery::create()->findPk($id);

        if (!$nav) {
            $this->getExternalResponse()->setStatusCode(404);
            $this->setStatusAndExit(false);
        }

        $nav->delete();
    }
}
