<?php

namespace App\Http\Controllers;

use App\Models\OfflineEntry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class OfflineEntryController extends Controller
{

    /**
     *
     * set user offline entry
     * @param User $user
     * @return RedirectResponse
     */
    public function setUserEntry(User $user)
    {
        $user->offlineEntries()->save(new OfflineEntry);

        return back();
    }

    /**
     *Set user has exited
     *
     * @param OfflineEntry $offlineEntry
     * @return RedirectResponse
     */
    public function setUserExit(OfflineEntry $offlineEntry)
    {
        $offlineEntry->exit();

        return back();
    }
}
