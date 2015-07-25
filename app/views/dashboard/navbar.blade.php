<nav class="navbar navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a href="{{ route('dashboard.index') }}" class="navbar-brand"><b>Intégration</b> UTT</a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('dashboard.referrals') }}">Parrains</a></li>
                <li><a href="{{ route('dashboard.validation') }}">Validation</a></li>
                <li><a href="{{ route('dashboard.administrators') }}">Administrateurs</a></li>
                <li><a href="{{ route('dashboard.newcomers') }}">Nouveaux</a></li>
                <li><a href="{{ route('dashboard.teams') }}">Équipes</a></li>
            </ul>
        </div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <li><a href="{{ route('oauth.logout') }}"><i class="fa fa-power-off"></i> Se déconnecter</a></li>
                </li>
            </ul>
        </div>
    </div>
</nav>
