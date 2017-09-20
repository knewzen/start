<?php

namespace Perfumerlabs\Start\Service;

use App\Model\User;
use Perfumerlabs\Start\Model\Duty;

class DutyFormatter
{
    /**
     * @param Duty $duty
     * @param User $user
     * @return array
     */
    public function format(Duty $duty, User $user)
    {
        $array = [
            'id' => $duty->getId(),
            'name' => $duty->getActivity()->getName(),
            'color' => $duty->getActivity()->getColor(),
            'readonly' => $duty->getActivity()->isReadonly(),
            'writable' => $duty->getActivity()->isWritable(),
            'postponable' => $duty->getActivity()->isPostponable(),
            'comment' => $duty->getComment(),
            'validation_url' => $duty->getValidationUrl(),
            'description' => $duty->getDescription()
        ];

        $iframe = $duty->getActivity()->getIframe() . '?_id=' . $duty->getId() . '&_activity=' . $duty->getActivity()->getCode();

        if ($duty->getQuery()) {
            $iframe .= '&' . $duty->getQuery();
        }

        $array['iframe'] = $iframe;

        return $array;
    }
}
