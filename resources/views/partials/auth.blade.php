
@guest
    <li class="nav-item">
        <a class="btn btn-dark mr-2" href="{{ route('login') }}">S'identifier</a>
    </li>
    @if (Route::has('register'))
        <li class="nav-item">
            <a class=" btn btn-dark " href="{{ route('register') }}">S'inscrire</a>
        </li>
    @endif
@else
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="http://localhost/keepitFresh/public/homeView">Mes commandes</a>

            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">

                Se déconnecter
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
@endguest
