<!-- Page Sidebar Start-->
<div class="page-sidebar">
    <div class="main-header-left d-none d-lg-block">
        <div class="logo-wrapper">
            <a href="{{ route('dashboard') }}">
                <img class="d-none d-lg-block blur-up lazyloaded sidebar-logo" src="{{ logo() }}" alt="">
            </a>
        </div>
    </div>
    <div class="sidebar custom-scrollbar">
        <a href="javascript:void(0)" class="sidebar-back d-lg-none d-block">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
        <div class="sidebar-user">
            <img class="img-60" src="{{ asset('assets/admin/images/logo/logo.png') }}" alt="#">
            <div>
                <h6 class="f-14">{{ admin()->first_name }}</h6>
                <p>{{ admin()->role }}</p>
            </div>
        </div>
        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <x-dashboard.sidebar.single-link title="Dashboard" link="{{ route('dashboard') }}" icon="home"/>

            {{-- Tours & Travel --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Tours & Travel</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Tours"
                icon="grid"
                :permissions="['tours.list','tours.create','tours.edit','tours.delete']"
                :children="[
                    ['title' => 'Tours', 'link' => route('dashboard.tours.index'), 'permissions' => ['tours.list','tours.edit','tours.delete'] ],
                    ['title' => 'Create Tour', 'link' => route('dashboard.tours.create'), 'permissions' => ['tours.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Categories"
                icon="book-open"
                :permissions="['categories.list','categories.create','categories.edit','categories.delete']"
                :children="[
                    ['title' => 'Categories', 'link' => route('dashboard.categories.index'), 'permissions' => ['categories.list','categories.edit','categories.delete'] ],
                    ['title' => 'Create Category', 'link' => route('dashboard.categories.create'), 'permissions' => ['categories.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Destinations"
                icon="globe"
                :permissions="['destinations.list','destinations.create','destinations.edit','destinations.delete']"
                :children="[
                    ['title' => 'Destinations', 'link' => route('dashboard.destinations.index'), 'permissions' => ['destinations.list','destinations.edit','destinations.delete'] ],
                    ['title' => 'Create Destination', 'link' => route('dashboard.destinations.create'), 'permissions' => ['destinations.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Tour Options"
                icon="check-square"
                :permissions="['tour-options.list','tour-options.create','tour-options.edit','tour-options.delete']"
                :children="[
                    ['title' => 'Tour Options', 'link' => route('dashboard.tour-options.index'), 'permissions' => ['tour-options.list','tour-options.edit','tour-options.delete'] ],
                    ['title' => 'Create Tour Option', 'link' => route('dashboard.tour-options.create'), 'permissions' => ['tour-options.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Tour Packages"
                icon="package"
                :permissions="['tour-packages.list','tour-packages.create','tour-packages.edit','tour-packages.delete']"
                :children="[
                    ['title' => 'Packages', 'link' => route('dashboard.tour-packages.index'), 'permissions' => ['tour-packages.list','tour-packages.edit','tour-packages.delete'] ],
                    ['title' => 'Create Package', 'link' => route('dashboard.tour-packages.create'), 'permissions' => ['tour-packages.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Tour Reviews"
                icon="message-square"
                :permissions="['tour-reviews.list','tour-reviews.create','tour-reviews.edit','tour-reviews.delete']"
                :children="[
                    ['title' => 'Tour Reviews', 'link' => route('dashboard.tour-reviews.index'), 'permissions' => ['tour-reviews.list'] ],
                ]"
            />

            <x-dashboard.sidebar.single-link :permissions="['comments.list']" title="Tour Comments" link="{{ route('dashboard.comments.index') }}" icon="message-circle" />

            {{-- Hotels & Accommodation --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Hotels & Accommodation</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Hotels"
                icon="sunrise"
                :permissions="['hotels.list','hotels.create','hotels.edit','hotels.delete']"
                :children="[
                    ['title' => 'Hotels', 'link' => route('dashboard.hotels.index'), 'permissions' => ['hotels.list','hotels.edit','hotels.delete'] ],
                    ['title' => 'Create Hotel', 'link' => route('dashboard.hotels.create'), 'permissions' => ['hotels.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Rooms"
                icon="square"
                :permissions="['rooms.list','rooms.create','rooms.edit','rooms.delete']"
                :children="[
                    ['title' => 'Rooms', 'link' => route('dashboard.rooms.index'), 'permissions' => ['rooms.list','rooms.edit','rooms.delete'] ],
                    ['title' => 'Create Room', 'link' => route('dashboard.rooms.create'), 'permissions' => ['rooms.create'] ],
                ]"
            />

            <x-dashboard.sidebar.single-link :permissions="['hotel_room_bookings.list']" title="Room Bookings" link="{{ route('dashboard.hotel_room_bookings.index') }}" icon="bookmark" />

            <x-dashboard.sidebar.link-with-child
                title="Amenities"
                icon="thumbs-up"
                :permissions="['amenities.list','amenities.create','amenities.edit','amenities.delete']"
                :children="[
                    ['title' => 'Amenities', 'link' => route('dashboard.amenities.index'), 'permissions' => ['amenities.list','amenities.edit','amenities.delete'] ],
                    ['title' => 'Create Amenity', 'link' => route('dashboard.amenities.create'), 'permissions' => ['amenities.create'] ],
                ]"
            />

            {{-- Transportation --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Transportation</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Trips"
                icon="navigation"
                :permissions="['trips.list','trips.create','trips.edit','trips.delete']"
                :children="[
                    ['title' => 'Trips', 'link' => route('dashboard.trips.index'), 'permissions' => ['trips.list','trips.edit','trips.delete'] ],
                    ['title' => 'Create Trip', 'link' => route('dashboard.trips.create'), 'permissions' => ['trips.create'] ],
                ]"
            />

            <x-dashboard.sidebar.single-link :permissions="['trip-bookings.list']" title="Trip Bookings" link="{{ route('dashboard.trip-bookings.index') }}" icon="clipboard" />

            <x-dashboard.sidebar.link-with-child
                title="Car Routes"
                icon="git-pull-request"
                :permissions="['car-routes.list','car-routes.create','car-routes.edit','car-routes.delete']"
                :children="[
                    ['title' => 'Car Routes', 'link' => route('dashboard.car-routes.index'), 'permissions' => ['car-routes.list','car-routes.edit','car-routes.delete'] ],
                    ['title' => 'Create Car Route', 'link' => route('dashboard.car-routes.create'), 'permissions' => ['car-routes.create'] ],
                ]"
            />

            {{-- Bookings & Orders --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Bookings & Orders</span></li>

            <x-dashboard.sidebar.single-link :permissions="['bookings.list']" title="Bookings" link="{{ route('dashboard.bookings.index') }}" icon="activity" />

            <x-dashboard.sidebar.link-with-child
                title="Custom Trips"
                icon="command"
                :permissions="['custom-trips.list']"
                :children="[
                    ['title' => 'All Requests', 'link' => route('dashboard.custom-trips.index'), 'permissions' => ['custom-trips.list'] ],
                    ['title' => 'Categories', 'link' => route('dashboard.customized-trip-categories.index'), 'permissions' => ['customized-trip-categories.list'] ],
                ]"
            />

            {{-- Content Management --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Content Management</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Blogs"
                icon="book"
                :permissions="['blogs.list','blogs.create','blogs.edit','blogs.delete']"
                :children="[
                    ['title' => 'Blog Categories', 'link' => route('dashboard.blog-categories.index'), 'permissions' => ['blog-categories.list','blog-categories.edit','blog-categories.delete'] ],
                    ['title' => 'Blogs', 'link' => route('dashboard.blogs.index'), 'permissions' => ['blogs.list','blogs.edit','blogs.delete'] ],
                    ['title' => 'Create Blog', 'link' => route('dashboard.blogs.create'), 'permissions' => ['blogs.create'] ],
                    ['title' => 'Blog Comments', 'link' => route('dashboard.blog-comments.index'), 'permissions' => ['blog-comments.list'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Pages"
                icon="layout"
                :permissions="['pages.list','pages.create','pages.edit','pages.delete']"
                :children="[
                    ['title' => 'Pages', 'link' => route('dashboard.pages.index'), 'permissions' => ['pages.list','pages.edit','pages.delete'] ],
                    ['title' => 'Create Page', 'link' => route('dashboard.pages.create'), 'permissions' => ['pages.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="FAQs"
                icon="layers"
                :permissions="['faqs.list','faqs.create','faqs.edit','faqs.delete']"
                :children="[
                    ['title' => 'FAQs', 'link' => route('dashboard.faqs.index'), 'permissions' => ['faqs.list','faqs.edit','faqs.delete'] ],
                    ['title' => 'Create FAQs', 'link' => route('dashboard.faqs.create'), 'permissions' => ['faqs.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="FAQ Categories"
                icon="help-circle"
                :permissions="['faq-categories.list','faq-categories.create']"
                :children="[
                    ['title' => 'Categories', 'link' => route('dashboard.faq-categories.index'), 'permissions' => ['faq-categories.list'] ],
                    ['title' => 'Create Category', 'link' => route('dashboard.faq-categories.create'), 'permissions' => ['faq-categories.create'] ],
                ]"
            />

            {{-- Marketing --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Marketing</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Newsletter"
                icon="mail"
                :permissions="['subscribes.list','newsletters.list']"
                :children="[
                    ['title' => 'Subscribers', 'link' => route('dashboard.subscribes.index'), 'permissions' => ['subscribes.list'] ],
                    ['title' => 'Add Subscriber', 'link' => route('dashboard.subscribes.create'), 'permissions' => ['subscribes.create'] ],
                    ['title' => 'Newsletters', 'link' => route('dashboard.newsletters.index'), 'permissions' => ['newsletters.list'] ],
                    ['title' => 'Create Newsletter', 'link' => route('dashboard.newsletters.create'), 'permissions' => ['newsletters.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Sliders"
                icon="image"
                :permissions="['sliders.list','sliders.create','sliders.edit','sliders.delete']"
                :children="[
                    ['title' => 'Sliders', 'link' => route('dashboard.sliders.index'), 'permissions' => ['sliders.list'] ],
                    ['title' => 'Create Slider', 'link' => route('dashboard.sliders.create'), 'permissions' => ['sliders.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Pricing"
                icon="percent"
                :permissions="['discounts.list','raises.list']"
                :children="[
                    ['title' => 'Discounts', 'link' => route('dashboard.discounts.index'), 'permissions' => ['discounts.list'] ],
                    ['title' => 'Create Discount', 'link' => route('dashboard.discounts.create'), 'permissions' => ['discounts.create'] ],
                    ['title' => 'Price Increases', 'link' => route('dashboard.raises.index'), 'permissions' => ['raises.list'] ],
                    ['title' => 'Create Increase', 'link' => route('dashboard.raises.create'), 'permissions' => ['raises.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Coupons"
                icon="tag"
                :permissions="['coupons.list','coupons.create','coupons.edit','coupons.delete']"
                :children="[
                    ['title' => 'Coupons', 'link' => route('dashboard.coupons.index'), 'permissions' => ['coupons.list','coupons.edit','coupons.delete'] ],
                    ['title' => 'Create Coupon', 'link' => route('dashboard.coupons.create'), 'permissions' => ['coupons.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Team"
                icon="users"
                :permissions="['employees.list','employees.create','employees.edit','employees.delete']"
                :children="[
                    ['title' => 'Team Members', 'link' => route('dashboard.employees.index'), 'permissions' => ['employees.list'] ],
                    ['title' => 'Add Member', 'link' => route('dashboard.employees.create'), 'permissions' => ['employees.create'] ],
                ]"
            />

            {{-- Support & Communication --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Support & Communication</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Contact Requests"
                icon="send"
                :permissions="['contact-requests.list']"
                :children="[
                    ['title' => 'Contact Requests', 'link' => route('dashboard.contact-requests.index'), 'permissions' => ['contact-requests.list'] ],
                ]"
            />

            <x-dashboard.sidebar.single-link :permissions="['appointments.list']" title="Appointments" link="{{ route('dashboard.appointments.index') }}" icon="calendar" />

            {{-- Users & Access --}}
            <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">Users & Access</span></li>

            <x-dashboard.sidebar.link-with-child
                title="Users"
                icon="user"
                :permissions="['users.list','users.create','users.edit','users.delete']"
                :children="[
                    ['title' => 'Users', 'link' => route('dashboard.users.index'), 'permissions' => ['users.list','users.edit','users.delete'] ],
                    ['title' => 'Create User', 'link' => route('dashboard.users.create'), 'permissions' => ['users.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Roles"
                icon="users"
                :permissions="['roles.list','roles.create','roles.edit','roles.delete']"
                :children="[
                    ['title' => 'Roles', 'link' => route('dashboard.roles.index'), 'permissions' => ['roles.list','roles.edit','roles.delete'] ],
                    ['title' => 'Create Role', 'link' => route('dashboard.roles.create'), 'permissions' => ['roles.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Clients"
                icon="user-check"
                :permissions="['clients.list','clients.create','clients.edit','clients.delete']"
                :children="[
                    ['title' => 'Clients', 'link' => route('dashboard.clients.index'), 'permissions' => ['clients.list','clients.edit','clients.delete'] ],
                    ['title' => 'Create Client', 'link' => route('dashboard.clients.create'), 'permissions' => ['clients.create'] ],
                ]"
            />

            {{-- System Settings --}}
                <li class="sidebar-header"><span style="color: #ff4c3b; font-weight: 600;">System Settings</span></li>

            <x-dashboard.sidebar.single-link :permissions="['settings.show']" title="Settings" link="{{ route('dashboard.settings.show') }}" icon="settings" />

            <x-dashboard.sidebar.link-with-child
                title="Currencies"
                icon="dollar-sign"
                :permissions="['currencies.list','currencies.create','currencies.edit','currencies.delete']"
                :children="[
                    ['title' => 'Currencies', 'link' => route('dashboard.currencies.index'), 'permissions' => ['currencies.list','currencies.edit','currencies.delete'] ],
                    ['title' => 'Create Currency', 'link' => route('dashboard.currencies.create'), 'permissions' => ['currencies.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Locations"
                icon="map-pin"
                :permissions="['locations.list','locations.create','locations.edit','locations.delete']"
                :children="[
                    ['title' => 'Locations', 'link' => route('dashboard.locations.index'), 'permissions' => ['locations.list','locations.edit','locations.delete'] ],
                    ['title' => 'Create Location', 'link' => route('dashboard.locations.create'), 'permissions' => ['locations.create'] ],
                ]"
            />

            <x-dashboard.sidebar.link-with-child
                title="Redirect Rules"
                icon="rotate-cw"
                :permissions="['redirect-rules.list','redirect-rules.create','redirect-rules.edit','redirect-rules.delete']"
                :children="[
                    ['title' => 'Redirect Rules', 'link' => route('dashboard.redirect-rules.index'), 'permissions' => ['redirect-rules.list','redirect-rules.edit','redirect-rules.delete'] ],
                    ['title' => 'Create Redirect Rule', 'link' => route('dashboard.redirect-rules.create'), 'permissions' => ['redirect-rules.create'] ],
                ]"
            />

            <x-dashboard.sidebar.single-link title="Media" class="open-media" link="javascript:;" icon="camera" :permissions="['media.access']" />

            <x-dashboard.sidebar.single-link title="Sitemap" class="generate-sitemap" link="{{ route('dashboard.sitemap.generate') }}" icon="download" :permissions="['media.access']" />

            <x-dashboard.sidebar.single-link title="Media Compression" link="{{ route('dashboard.media-compression.index') }}" icon="image" :permissions="['media.access']" />

            <x-dashboard.sidebar.single-link title="API Documentation" link="{{ route('dashboard.api-documentation.index') }}" icon="code" :permissions="['api-documentation.list']" />

            {{-- Logout --}}
            <x-dashboard.sidebar.single-link title="Logout" link="{{ route('logout') }}" icon="log-in" />

        </ul>
    </div>
</div>
<!-- Page Sidebar Ends-->
