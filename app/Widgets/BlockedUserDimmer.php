<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;
use App\BlockedUsers;

class BlockedUserDimmer extends AbstractWidget
{
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
    public function run()
    {
        $count = BlockedUsers::count();
       
        $string = $count == 1 ? 'blocked user' : 'blocked users';

        return view('dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'color'   => '#D61D16',
            'title'  => "{$count} {$string}",
            'text'   => "You have {$count} {$string} in your database. Click on button below to view all users.",
            'button' => [
                'text' => 'View all blocked users',
                'link' => route('voyager.blocked.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/02.png'),
        ]));
    }
}
