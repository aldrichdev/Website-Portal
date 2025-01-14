@extends('template')
@section('content')
    <div>
        <img src="{{ asset('img/logo.png') }}" style="height: 120px;" alt=""/>
    </div>

    <!--
    <div class="d-block online-count" style="font-size: 14px;">
        <ul class="menu">
            @/guest

                <li><a href="{/{ route('Secure_Login') }}">{/{ __('Secure Login') }}</a></li>
            @/else
                <li>
                    <label for="drop-5" class="toggle">{/{ Auth::user()->username }} <i
                                class="fas fa-caret-down"></i></label>
                    <a href="#">{/{ Auth::user()->username }}</a>
                    <input type="checkbox" id="drop-5" style="display: none !important;"/>
                    <ul>
                        <li><a href="{/{ route('Logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{/{ __('Logout') }}</a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{/{ route('Logout') }}" method="POST"
                          style="display: none;">
                        @/csrf
                    </form>
                </li>
            @/endguest
        </ul>
    </div>
    -->

    <!--Latest news-->
    <table>
        <tbody>
        <tr>
            <td>
                <table style="padding: 0; background: black;">
                    <tbody>
                    <tr>
                        <td>
                            <img src="{{ asset('img/fm_top.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table background="{{ asset('img/fm_middle.gif') }}"
                       style="padding: 0;background-repeat:no-repeat; background-color: black; width: 500px;">
                    <tbody>
                    <tr>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                        <td>
                            <div class="pb-3"></div>
                            <span class="d-block text-center">
                                <b>Latest News and Updates</b>
                            </span>
                            <div class="pb-3"></div>
                            <table style="padding: 0; background: black;">
                                <tbody>
                                <tr class="align-top">
                                    <td style="width: 100px;">
                                        <a href="/board/viewforum.php?f=2">
                                            <img class="mx-auto"
                                                 src="{{ asset('img/mm_scroll.jpg') }}"
                                                 alt="">
                                        </a>
                                    </td>
                                    <td style="width: 350px">
                                        <table id="List" class="container">
                                            @foreach ($news_feed as $news)
                                                <tr>
                                                    <td class="w-75">
                                                        <!-- News subject -->
                                                        <a class="c"
                                                           href="/board/viewtopic.php?f={{ $news->forum_id }}&p={{ $news->post_id }}">
                                                            @php
                                                                echo Str::limit(strip_tags($news->post_subject), 37);
                                                            @endphp
                                                        </a>
                                                    </td>
                                                    <td class="w-25">
                                                            <span class="text-white float-right">
                                                                @php
                                                                    $timestamp = $news->topic_time;
                                                                    $dt = new DateTime();
                                                                    echo $dt->setTimestamp( $timestamp )->format("d-M-Y ");
                                                                @endphp
                                                            </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                </tbody>
                            </table>
                            <div class="text-center pb-2">To view a full list of news and
                                updates,
                                <a href="/board/viewforum.php?f=2" class="c">click here</a>.
                            </div>
                        </td>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                    </tr>
                    </tbody>
                </table>
                <table style="padding: 0;">
                    <tbody>
                    <tr>
                        <td colspan="3">
                            <img src="{{ asset('img/fm_bottom.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="pb-3"></div>

                <!--World list-->
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <table style="padding: 0; background: black;">
                                <tbody>
                                <tr>
                                    <td>
                                        <img src="{{ asset('img/fm_top.gif') }}" alt="">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table background="{{ asset('img/fm_middle.gif') }}"
                                   style="padding: 0;background-repeat:no-repeat; background-color: black; width: 500px;">
                                <tbody>
                                <tr>
                                    <td style="width: 7px;" background="{{ asset('img/fm_middle.gif') }}"></td>
                                    <td>
                                        <div class="pt-3"></div>

                                        <span class="d-block text-center">
                                            <b>Anyone may play on the worlds below</b>
                                        </span>

                                        <div class="pb-3"></div>

                                        <div class="d-flex justify-content-center">
                                            <table style="padding: 0; background: black;">
                                                <tbody>

                                                <!--World online counts-->
                                                <tr>
                                                    <td colspan="2">
                                                        <img align="left" src="{{ asset('img/usflag.gif') }}" width="30"
                                                             height="15" border="0">
                                                        <span class="m-1">Raleigh</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="center"><a
                                                                style="color: rgb(144, 192, 64) !important; TEXT-DECORATION: none;"
                                                                href="http://game.openrsc.com/play/preservation/members">RSC Preservation</a></td>
                                                    <td align="right">{{$preservation_online}} players</td>
                                                </tr>
                                                <tr>
                                                    <td valign="center"><a
                                                                style="color: rgb(144, 192, 64) !important; TEXT-DECORATION: none;"
                                                                href="http://game.openrsc.com/play/cabbage/members">RSC Cabbage</a></td>
                                                    <td align="right">{{$cabbage_online}} players</td>
                                                </tr>
                                                <tr>
                                                    <td valign="center"><a
                                                                style="color: rgb(144, 192, 64) !important; TEXT-DECORATION: none;"
                                                                href="http://game.openrsc.com/play/uranium/members">RSC Uranium</a></td>
                                                    <td align="right">{{$uranium_online}} cyborgs</td>
                                                </tr>
                                                <tr>
                                                    <td valign="center"><a
                                                                style="color: rgb(144, 192, 64) !important; TEXT-DECORATION: none;"
                                                                href="http://game.openrsc.com/play/coleslaw/members">RSC Coleslaw</a></td>
                                                    <td align="right">{{$coleslaw_online}} cyborgs</td>
                                                </tr>
                                                <!--<tr>
                                                    <td valign="center"><a
                                                                style="color: rgb(144, 192, 64) !important; TEXT-DECORATION: none;"
                                                                href="/play/2001scape">2001scape</a></td>
                                                    <td align="right">{/{$retro_online}} players</td>
                                                </tr>
                                                <tr>
                                                    <td valign="center"><a
                                                                style="color: rgb(144, 192, 64) !important; TEXT-DECORATION: none;"
                                                                href="/play/openpk">Open PK</a></td>
                                                    <td align="right">{/{$openpk_online}} players</td>
                                                </tr>-->
                                                <!--end worlds-->
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="pb-3"></div>
                                    </td>
                                    <td style="width: 7px;" background="{{ asset('img/fm_middle.gif') }}"></td>
                                </tr>
                                </tbody>
                            </table>
                            <table style="padding: 0;">
                                <tbody>
                                <tr>
                                    <td colspan="3">
                                        <img src="{{ asset('img/fm_bottom.gif') }}" alt="">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="pb-3"></div>

                <!--Play game-->
                <table style="padding: 0; background: black;">
                    <tbody>
                    <tr>
                        <td>
                            <img src="{{ asset('img/fm_top.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table background="{{ asset('img/fm_middle.gif') }}"
                       style="padding: 0;background-repeat:no-repeat; background-color: black; width: 500px;">
                    <tbody>
                    <tr>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                        <td class="align-bottom">
                            <div style="text-align: center;">
                                <div class="pb-3"></div>
                                <table>
                                    <tbody>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="/playnow">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_sword.jpg') }}"
                                                     alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="/playnow">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b2"
                                                        background="{{ asset('img/shinystonered.jpg') }}"
                                                        style="background-color: #570700">
                                                        <div class="text-center">
                                                            <b>Play
                                                                <span class="d-block">
                                                                    Game
                                                                </span>
                                                            </b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Play RuneScape right now!
                                            <div class="d-block">
                                                <a href="/playnow" class="c">
                                                    Click here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px"></td>
                                        <td style="width: 100px;">
                                            <a href="{{ route('Player Registration') }}"
                                               class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_player.jpg') }}"
                                                     alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="{{ route('Player Registration') }}"
                                                       class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="width: 100px; padding: 0; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b2"
                                                        background="{{ asset('img/shinystonered.jpg') }}"
                                                        style="background-color: #570700">
                                                        <div class="text-center">
                                                            <b>Create Account</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Create an account for both the game and our website.
                                            <div class="d-block">
                                                <a href="{{ route('Player Registration') }}"
                                                   class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="https://classic.runescape.wiki/w/RuneScape_Classic" target="_blank"
                                               class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_whyrs.jpg') }}"
                                                     alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="https://classic.runescape.wiki/w/RuneScape_Classic"
                                                       target="_blank" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747">
                                                        <div class="text-center">
                                                            <b>Why Choose RuneScape?</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            See why our game is right for you!
                                            <div class="d-block">
                                                <a href="https://classic.runescape.wiki/w/RuneScape_Classic"
                                                   target="_blank" class="c">Click Here</a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="https://classic.runescape.wiki/w/Pay-to-play" target="_blank"
                                               class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_members.jpg') }}"
                                                     alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="https://classic.runescape.wiki/w/Pay-to-play"
                                                       target="_blank"
                                                       class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>RuneScape Members</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Everyone may be a member for free!
                                            <div class="d-block">
                                                <a href="https://classic.runescape.wiki/w/Pay-to-play" target="_blank"
                                                   class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="/hiscores/preservation" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_chalice.jpg') }}"
                                                     alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="/hiscores/preservation" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Hiscore Tables</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Is your character in the top 250,000?
                                            <div class="d-block">
                                                <a href="/hiscores/preservation" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="/hiscores/preservation" class="c">
                                                <img src="{{ asset('img/mm2_rs2b.jpg') }}"
                                                     alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="https://2009scape.org" target="_blank" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Play RS2 Beta</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Try the new version
                                            <span class="d-block">
                                                of the game!</span>
                                            <div class="d-block">
                                                <a href="https://2009scape.org" target="_blank" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pb-3"></div>
                        </td>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                    </tr>
                    </tbody>
                </table>
                <table style="padding: 0;">
                    <tbody>
                    <tr>
                        <td colspan="3">
                            <img src="{{ asset('img/fm_bottom.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="pb-3"></div>

                <!--Secure Services-->
                <table style="padding: 0; background-color: black;">
                    <tbody>
                    <tr>
                        <td>
                            <img src="{{ asset('img/fm_top.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table background="{{ asset('img/fm_middle.gif') }}"
                       style="padding: 0;background-repeat:no-repeat; background-color: black; width: 500px;">
                    <tbody>
                    <tr>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                        <td class="align-bottom">
                            <div style="text-align: center;">
                                <img src="{{ asset('img/blank.gif') }}" height="7" width="1"
                                     alt="">
                                <div class="pb-3"></div>
                                <b>Secure Services</b>
                                <div class="pb-3"></div>
                                <table>
                                    <tbody>
                                    <!--<tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_subscribe.jpg') }}"
                                                     height="120" width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Subscribe</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Start or continue your subscription
                                            <div class="d-block">
                                                <a href="" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_unsubscribe.jpg') }}"
                                                     height="120" width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Unsubscribe</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Cancel your subscription
                                            <div class="d-block">
                                                <a href="" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>-->
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="https://discord.gg/ABdFCqn" target="_blank">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_support.jpg') }}"
                                                     height="120" width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="https://discord.gg/ABdFCqn" target="_blank">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Customer Support</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Questions?
                                            <span class="d-block">
                                                Contact our staff
                                            </span>
                                            <div class="d-block">
                                                <a href="https://discord.gg/ABdFCqn" target="_blank" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="/board/ucp.php?i=pm&folder=inbox" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_inbox.jpg') }}"
                                                     height="120"
                                                     width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="/board/ucp.php?i=pm&folder=inbox"
                                                       class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Message Centre</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Your messages
                                            <span class="d-block">
                                                from our staff
                                            </span>
                                            <div class="d-block">
                                                <a href="/board/ucp.php?i=pm&folder=inbox" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="/board" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mms_forums.jpg') }}"
                                                     height="120" width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="/board" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Forums</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Discuss the game with fellow players!
                                            <div class="d-block">
                                                <a href="/board" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="/login" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mms_accman.jpg') }}"
                                                     height="120" width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="/login" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Account Management</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Manage your Password and Recovery Details
                                            <div class="d-block">
                                                <a href="/login" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pb-3"></div>
                        </td>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                    </tr>
                    </tbody>
                </table>
                <table style="padding: 0;">
                    <tbody>
                    <tr>
                        <td colspan="3">
                            <img src="{{ asset('img/fm_bottom.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="pb-3"></div>
                <table style="padding: 0; background: black;">
                    <tbody>
                    <tr>
                        <td>
                            <img src="{{ asset('img/fm_top.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table background="{{ asset('img/fm_middle.gif') }}"
                       style="padding: 0;background-repeat:no-repeat; background-color: black; width: 500px;">
                    <tbody>
                    <tr>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                        <td class="align-bottom">
                            <div style="text-align: center;">
                                <img src="{{ asset('img/blank.gif') }}" height="7" width="1"
                                     alt="">
                                <div class="pb-3"></div>
                                <b>Manual</b>
                                <div class="pb-3"></div>
                                <table>
                                    <tbody>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="https://rsc.wiki" target="_blank" class="c">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_howtoplay.jpg') }}"
                                                     height="120" width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="https://rsc.wiki" target="_blank" class="c">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>How To Play</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Everything you need to know to play
                                            RuneScape
                                            <div class="d-block">
                                                <a href="https://rsc.wiki" target="_blank" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="{{ route('Frequently Asked Questions') }}">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_faq.jpg') }}"
                                                     height="120"
                                                     width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="{{ route('Frequently Asked Questions') }}">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>F.A.Q.</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Answers to Frequently Asked Questions
                                            <div class="d-block">
                                                <a href="{{ route('Frequently Asked Questions') }}"
                                                   class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr class="align-top">
                                        <td style="width: 100px;">
                                            <a href="https://classic.runescape.wiki/w/Library_of_Varrock"
                                               target="_blank">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_lov.jpg') }}"
                                                     height="120"
                                                     width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="https://classic.runescape.wiki/w/Library_of_Varrock"
                                                       target="_blank">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747;">
                                                        <div class="text-center">
                                                            <b>Library of Varrock</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Learn about the History of RuneScape
                                            <div class="d-block">
                                                <a href="https://classic.runescape.wiki/w/Library_of_Varrock"
                                                   target="_blank" class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 10px;"></td>
                                        <td style="width: 100px;">
                                            <a href="{{ route('Rules and Security') }}">
                                                <img class="mx-auto"
                                                     src="{{ asset('img/mm_rules.jpg') }}"
                                                     height="120"
                                                     width="77" alt="">
                                            </a>
                                        </td>
                                        <td style="width: 120px;">
                                            <div style="left: 0; top: 0; position: relative;">
                                                <div style="left: 0; top: 0; position: absolute;">
                                                    <a href="{{ route('Rules and Security') }}">
                                                        <img src="{{ asset('img/blank.gif') }}"
                                                             height="45" width="100"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <table style="height: 45px; width: 100px; padding: 2px; background-color: black;">
                                                <tbody>
                                                <tr>
                                                    <td class="b"
                                                        background="{{ asset('img/stoneback.gif') }}"
                                                        style="background-color: #474747">
                                                        <div class="text-center">
                                                            <b>Rules & Security</b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            Learn our rules
                                            <span class="d-block">
                                                and stay safe
                                            </span>
                                            <span class="d-block">
                                                online
                                            </span>
                                            <div class="d-block">
                                                <a href="{{ route('Rules and Security') }}"
                                                   class="c">
                                                    Click Here
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pb-3"></div>
                        </td>
                        <td style="width: 7px;"
                            background="{{ asset('img/fm_middle.gif') }}"></td>
                    </tr>
                    </tbody>
                </table>
                <table style="padding: 0;">
                    <tbody>
                    <tr>
                        <td colspan="3">
                            <img src="{{ asset('img/fm_bottom.gif') }}" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="pb-2"></div>
            </td>
        </tr>
        </tbody>
    </table>
@endsection