<?php

namespace Modules\CostOverview\Listeners;

use App\Events\Menu\AdminCreated as Event;

class ShowInMenu
{
    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $menu = $event->menu;

        $title = trans('cost-overview::general.cost_overviews');

        // Add Cost Overview menu item under Sales section if permission allows
        // For now, we add it as a standalone menu item
        // You can customize the permission check based on your needs
        
        $menu->route('cost-overviews.index', $title, [], 35, [
            'icon' => 'assessment',
        ]);
    }
}
