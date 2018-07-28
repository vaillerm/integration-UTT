@extends(Auth::user() ?
    (Auth::user()->isNewcomer() ?
        'layouts.newcomer'
        : 'layouts.dashboard'
    )
    : 'layouts.external'
)
