<?php

namespace App\Http\Controllers\Seeker\Concerns;

use Illuminate\Database\Eloquent\Model;

trait EnsuresOwnership
{
    protected function ensureOwnedByCurrentUser(Model $record): void
    {
        abort_unless($record->getAttribute('user_id') === auth()->id(), 403, 'Data ini bukan milik Anda.');
    }
}
