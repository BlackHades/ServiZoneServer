<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class ReportedUserDimmer extends AbstractWidget {

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run() {
        $count = \App\ReportedUsers::count();
        $string = $count == 1 ? 'reported user' : 'reported users';

        return view('dimmer', array_merge($this->config, [
            'icon' => 'voyager-group',
            'color' => '#ffa726',
            'title' => "{$count} {$string}",
            'text' => "You have {$count} {$string} in your database. Click on button below to view all users.",
            'button' => [
                'text' => 'View all Experts',
                'link' => route('voyager.reported-users.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/02.png'),
        ]));
    }

}
