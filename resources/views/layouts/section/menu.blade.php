<div class="m-header__bottom">
    <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
        <div class="m-stack m-stack--ver m-stack--desktop">

            <!-- begin::Horizontal Menu -->
            <?php
            $active_dash = $active_people = $active_event = $active_group = '';
            $path = explode('/', Request::path());

            ?>
            <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                        <li class="m-menu__item {{ $path[0] == 'home' ?  'm-menu__item--active' : ''}}" aria-haspopup="true"><a href="/home" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text"><i class="fa fa-home"></i></span></a></li>
                        <li class="m-menu__item {{ $path[0] == 'people' ?  'm-menu__item--active' : ''}}" aria-haspopup="true"><a href="/people" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">People</span></a></li>
                        <li class="m-menu__item {{ $path[0] == 'event' ?  'm-menu__item--active' : ''}}" aria-haspopup="true"><a href="/event" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Events</span></a></li>
                        <li class="m-menu__item {{ $path[0] == 'group' ?  'm-menu__item--active' : ''}}" aria-haspopup="true"><a href="/group" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Groups</span></a></li>
                        <li class="m-menu__item {{ $path[0] == 'group' ?  'm-menu__item--active' : ''}}" aria-haspopup="true"><a href="/account/1" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Management</span></a></li>

                    </ul>
                </div>
            </div>

            <!-- end::Horizontal Menu -->

            <!--begin::Search-->
            <div class="m-stack__item m-stack__item--middle m-dropdown m-dropdown--arrow m-dropdown--large m-dropdown--mobile-full-width m-dropdown--align-right m-dropdown--skin-light m-header-search m-header-search--expandable m-header-search--skin-" id="m_quicksearch"
                 m-quicksearch-mode="default">

                <!--begin::Search Form -->
                <form class="m-header-search__form">
                    <div class="m-header-search__wrapper">
                        <span class="m-header-search__icon-search" id="m_quicksearch_search">
                            <i class="la la-search"></i>
                        </span>
                        <span class="m-header-search__input-wrapper">
                            <input autocomplete="off" type="text" name="q" class="m-header-search__input" value="" placeholder="Search..." id="m_quicksearch_input">
                        </span>
                        <span class="m-header-search__icon-close" id="m_quicksearch_close">
                            <i class="la la-remove"></i>
                        </span>
                        <span class="m-header-search__icon-cancel" id="m_quicksearch_cancel">
                            <i class="la la-remove"></i>
                        </span>
                    </div>
                </form>

                <!--end::Search Form -->

                <!--begin::Search Results -->
                <div class="m-dropdown__wrapper">
                    <div class="m-dropdown__arrow m-dropdown__arrow--center"></div>
                    <div class="m-dropdown__inner">
                        <div class="m-dropdown__body">
                            <div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-height="300" data-mobile-height="200">
                                <div class="m-dropdown__content m-list-search m-list-search--skin-light">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end::Search Results -->
            </div>

            <!--end::Search-->
        </div>
    </div>
</div>