<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('includes.header')
<body>

<nav>
    <label for="drop" class="toggle">
        <i class="fas fa-bars"></i>
        Navigation
    </label>

    <!-- Left side of Navbar -->
    <input type="checkbox" id="drop" style="display: none !important;"/>
    <ul class="menu">
        <li><a href="{/{ route('home') }}">Home</a></li>
        <li>
            <label for="drop-1" class="toggle">Download <i class="fas fa-caret-down"></i></label>
            <a href="#">Download</a>
            <input type="checkbox" id="drop-1" style="display: none !important;"/>
            <ul>
                <li><a href="{{ asset('downloads/OpenRSC%20Launcher.exe') }}">Windows</a></li>
                <li><a href="{{ asset('downloads/OpenRSC.jar') }}">Mac / Linux</a></li>
                <li><a href="{{ asset('downloads/openrsc.apk') }}">Android App</a></li>
                <li>
                    <a href="https://gitlab.openrsc.com/open-rsc/Single-Player/-/releases">Single
                        Player</a></li>
                <li><a href="https://gitlab.openrsc.com/open-rsc/Game" target="_blank">Source Code</a></li>
            </ul>

        </li>
        <li>
            <label for="drop-2" class="toggle">Community <i class="fas fa-caret-down"></i></label>
            <a href="#">Community</a>
            <input type="checkbox" id="drop-2" style="display: none !important;"/>
            <ul>
                <li><a href="https://discord.gg/ABdFCqn" target="_blank">
                        <i class="fab fa-discord"></i>
                        Discord</a></li>
                <li><a href="https://orsc.dev" target="_blank">
                        <i class="fab fa-gitlab"></i>
                        Source Code</a></li>
                <li><a href="https://www.reddit.com/r/rsc" target="_blank">
                        <i class="fab fa-reddit-alien"></i>
                        Reddit</a></li>
            </ul>
        </li>
        <li><a href="{{ asset('highscores') }}">Highscores</a></li>
        <li>
            <label for="drop-3" class="toggle">Information <i class="fas fa-caret-down"></i></label>
            <a href="#">Information</a>
            <input type="checkbox" id="drop-3" style="display: none !important;"/>
            <ul>
                <li><a href="{{ asset('faq') }}">FAQ</a></li>
                <li><a href="{{ asset('rules') }}">Rules</a></li>
                <li><a href="{{ asset('/player/shar/bank') }}">Shar's Bank</a></li>
                <li><a href="{{ asset('stats') }}">Game Statistics</a></li>
            </ul>
        </li>
        <li>
            <label for="drop-4" class="toggle">Guides <i class="fas fa-caret-down"></i></label>
            <a href="#">Guides</a>
            <input type="checkbox" id="drop-4" style="display: none !important;"/>
            <ul>
                <li><a href="{{ asset('quest_list') }}">Quest List</a></li>
                <li><a href="{{ asset('minigame_list') }}">Minigames</a></li>
                <li><a href="{{ asset('wilderness') }}">Wilderness Map</a></li>
                <li><a href="{{ route('items') }}">Item Database</a></li>
                <li><a href="{{ asset('npcs') }}">NPC Database</a></li>
            </ul>
        </li>
        <li>
            <label for="drop-5" class="toggle">Reports <i class="fas fa-caret-down"></i></label>
            <a href="#">Reports</a>
            <input type="checkbox" id="drop-5" style="display: none !important;"/>
            <ul>
                <li><a href="https://gitlab.openrsc.com/open-rsc/Game/issues" target="_blank">Bug Reports</a></li>
            </ul>
        </li>
        <li><a href="{{ asset('worldmap') }}">Live Map</a></li>
        @if(Auth::user())
            <li>
                <label for="drop-5" class="toggle">Staff <i class="fas fa-caret-down"></i></label>
                <a href="#">Staff</a>
                <input type="checkbox" id="drop-5" style="display: none !important;"/>
                <ul>
                    <li><a href="{{ asset('chat_logs') }}">Chat Logs</a></li>
                    <li><a href="{{ asset('pm_logs') }}">PM Logs</a></li>
                    <li><a href="{{ asset('trade_logs') }}">Trade Logs</a></li>
                    <li><a href="{{ asset('generic_logs') }}">Generic Logs</a></li>
                    <li><a href="{{ asset('shop_logs') }}">Shop Logs</a></li>
                @if(str_contains(url()->current(), '/hiscores/cabbage') || str_contains(url()->current(), '/hiscores/coleslaw')) <!-- fix this later -->
                    <li><a href="{{ asset('auction_logs') }}">Auction Logs</a></li>
                    @endif
                    <li><a href="{{ asset('live_feed_logs') }}">Live Feed Logs</a></li>
                    <li><a href="{{ asset('player_cache_logs') }}">Player Cache Logs</a></li>
                    <li><a href="{{ asset('report_logs') }}">Report Logs</a></li>
                    <li><a href="{{ asset('staff_logs') }}">Staff Logs</a></li>
                </ul>
            </li>
        @endif
    </ul>

    <!-- Right side of Navbar -->
    <ul class="menu">
        <!-- Authentication Links -->
        @guest
            <li><a href="{/{ route('login') }}">{{ __('Staff Login') }}</a></li>
            @if (Route::has('staff_register'))
                <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
            @endif
        @else
            <li>
                <label for="drop-5" class="toggle">{{ Auth::user()->username }} <i
                            class="fas fa-caret-down"></i></label>
                <a href="#">{{ Auth::user()->username }}</a>
                <input type="checkbox" id="drop-5" style="display: none !important;"/>
                <ul>
                    <li><a href="{/{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    </li>
                </ul>
                <form id="logout-form" action="{/{ route('logout') }}" method="POST"
                      style="display: none;">
                    @csrf
                </form>
            </li>
        @endguest
    </ul>
</nav>

<div class="navbar-expand-xxl pt-2 mr-1">
    <div class="e text-center flex-row" style="background: black; width:596px;">
        <span class="flex-auto p-2">
            <a class="c" href="/" taborder="1">Home</a>
        </span>
        <span class="flex-auto p-2 dropdown">
            <a class="c" href="/download" taborder="1">Play Now <i class="fas fa-caret-down"></i></a>
            <span class="p-2 dropdown-content" style="background:black; width:110px;">
                <a class="c text-left" target="_blank" rel="noopener" href="https://classic.runescape.wiki">
                    RSC Wiki
                </a>
                <a class="c text-left" target="_blank" rel="noopener" href="/wiki">
                    OpenRSC Wiki
                </a>
                <a class="c text-left" target="_blank" rel="noopener" href="https://rsc.plus">
                RSC+
            </a>
            </span>
        </span>
        <span class="flex-auto p-2 dropdown">
            <a class="c" href="#" taborder="1">Hiscores <i class="fas fa-caret-down"></i></a>
            <span class="p-2 dropdown-content" style="background:black; width:130px;">
                <a class="c text-left" href="/hiscores/preservation">RSC Preservation</a>
                <a class="c text-left" href="/hiscores/preservation">RSC Cabbage</a>
                <a class="c text-left" href="/hiscores/preservation">RSC Uranium</a>
                <a class="c text-left" href="/hiscores/preservation">RSC Coleslaw</a>
            </span>
        </span>
        <span class="flex-auto p-2">
            <a class="c" href="/board" taborder="1">Forums</a>
        </span>
        <span class="flex-auto p-2">
            <a class="c" target="_blank" rel="noopener" href="https://discord.gg/ABdFCqn" taborder="2">Discord</a>
        </span>
        <span class="flex-auto p-2">
            <a class="c" target="_blank" rel="noopener" href="https://gitlab.com/open-runescape-classic"
               taborder="3">Open Source</a>
        </span>
        <span class="flex-auto p-2 dropdown"><a href="#" rel="noopener" taborder="5">Wiki Lookup <i
                        class="fas fa-caret-down"></i></a>
            <span class="p-2 dropdown-content" style="background:black; width:110px;">
                <a class="c text-left" target="_blank" rel="noopener" href="https://classic.runescape.wiki">
                    RSC Wiki
                </a>
                <a class="c text-left" target="_blank" rel="noopener" href="/wiki">
                    OpenRSC Wiki
                </a>
            </span>
        </span>
    </div>
</div>

@if(Route::currentRouteName() == 'World_Map')
    <table style="width: 250px; background: black; padding: 4px;">
        <tbody>
        <tr>
            <td class=e>
                <div class="text-center">
                    @if(Route::currentRouteName())
                        <b>{{ preg_replace("/[^A-Za-z0-9 ]/", " ", Route::currentRouteName()) }}</b>
                    @else
                        {{ ucfirst($subpage) }}
                    @endif
                    <span class="d-block">
                        <a class="c" href="{{ route('Home') }}">Main menu</a>
                    </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="pt-2"></div>
    @yield('content')

@elseif(Route::currentRouteName() == 'Wilderness_Map')
    <table style="width: 250px; background: black; padding: 4px;">
        <tbody>
        <tr>
            <td class=e>
                <div class="text-center">
                    @if(Route::currentRouteName())
                        <b>{{ preg_replace("/[^A-Za-z0-9 ]/", " ", Route::currentRouteName()) }}</b>
                    @else
                        {{ ucfirst($subpage) }}
                    @endif
                    <span class="d-block">
                        <a class="c" href="{{ route('Home') }}">Main menu</a>
                    </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="pt-2"></div>
    @yield('content')

@else
    <main>
        <section class="top-border">
            <div class="top-left-border"></div>
            <div class="top-middle-border"></div>
            <div class="top-right-border"></div>
        </section>

        <section class="middle">
            <div class="mid-left-border"></div>
            <div class="middle-content">
                @if(Route::currentRouteName() != 'Home')
                    <table style="width: 250px; background: black; padding: 4px;">
                        <tbody>
                        <tr>
                            <td class=e>
                                <div class="text-center">
                                    @if(str_contains(url()->current(), '/player'))
                                        <b>RuneScape Hiscores</b>
                                    @elseif(Route::currentRouteName())
                                        <b>{{ preg_replace("/[^A-Za-z0-9 ]/", " ", Route::currentRouteName()) }}</b>
                                    @elseif(in_array($subpage, array('skill_total', 'attack', 'defense', 'strength', 'hits', 'ranged', 'prayer', 'magic', 'cooking', 'fletching', 'fishing', 'firemaking', 'crafting', 'smithing', 'mining', 'agility', 'thieving', 'runecraft', 'harvesting')))
                                        <b>RuneScape Hiscores</b>
                                    @else
                                        <b>{{ ucfirst($subpage) }}</b>
                                    @endif
                                    <div class="d-block">
                                        @if(str_contains(url()->current(), '/player'))
                                            <a class="c" href="{{ route('Home') }}">
                                                Main menu
                                            </a> -
                                            <a class="c" href="/hiscores/{{ $db ?? 'preservation' }}">
                                                All Hiscores
                                            </a>
                                        @else
                                            <a class="c" href="{{ route('Home') }}">Main menu</a>
                                        @endif
                                    </div>
                                    @if(str_contains(url()->current(), '/hiscores/cabbage') || str_contains(url()->current(), '/hiscores/coleslaw'))
                                        @if(in_array($subpage ?? '', array('skill_total', 'attack', 'defense', 'strength', 'hits', 'ranged', 'prayer', 'magic', 'cooking', 'fletching', 'fishing', 'firemaking', 'crafting', 'smithing', 'mining', 'agility', 'thieving', 'runecraft', 'harvesting')) || route('RuneScape Hiscores',$db))
                                            <div class="d-block">
                                                @if($subpage ?? '' == 'skill_total')
                                                    <a class="c" href="/hiscores/{{ $db ?? 'preservation' }}">All</a> |
                                                    <a class="c"
                                                       href="/hiscores/{{ $db ?? 'preservation' }}/{{ $subpage ?? '' }}/1">Ironman</a>
                                                    |
                                                    <a class="c"
                                                       href="/hiscores/{{ $db ?? 'preservation' }}/{{ $subpage ?? '' }}/2">Hardcore</a>
                                                    |
                                                    <a class="c"
                                                       href="/hiscores/{{ $db ?? 'preservation' }}/{{ $subpage ?? '' }}/3">Ultimate</a>
                                                @else
                                                    <a class="c" href="/hiscores/{{ $db ?? 'preservation' }}">All</a> |
                                                    <a class="c"
                                                       href="/hiscores/{{ $db ?? 'preservation' }}/skill_total/1">Ironman</a>
                                                    |
                                                    <a class="c"
                                                       href="/hiscores/{{ $db ?? 'preservation' }}/skill_total/2">Hardcore</a>
                                                    |
                                                    <a class="c"
                                                       href="/hiscores/{{ $db ?? 'preservation' }}/skill_total/3">Ultimate</a>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="pt-3"></div>
                @endif
                @yield('content')
            </div>
            <div class="pt-2"></div>
            </div>
            <div class="mid-right-border"></div>
        </section>
        <section class="bottom-border">
            <div class="bottom-left-border"></div>
            <div class="bottom-middle">
                <div class="copyright pt-2">
                    Open RuneScape Classic is not affiliated with RuneScape Classic nor JaGeX.<br>
                    To use our service you must agree to our
                    <a class="c" href="{{ route('Terms_and_Conditions') }}">Terms+Conditions</a>
                    +
                    <a class="c" href="{{ route('Privacy_Policy') }}">Privacy policy</a>
                </div>
                <div class="bottom-middle-border"></div>
            </div>
            <div class="bottom-right-border"></div>
        </section>
    </main>
@endif

@include('includes.footerscripts')
</body>
</html>
