<li class="onhover-dropdown">
    <i data-feather="bell"></i>
    @if($unread_notifications->count() >0)
        <span
            class="badge badge-pill badge-primary pull-right notification-badge">{{$unread_notifications->count()}}</span>
    @endif
    <span class="dot"></span>
    <ul class="notification-dropdown onhover-show-div p-0">
        <li>Notification
            @if($unread_notifications->count() > 0 )
                <span class="badge badge-pill badge-primary pull-right">{{$unread_notifications->count()}}</span>
            @endif
        </li>
        @forelse($unread_notifications as $notification)
            <li>
                <div class="media">
                    <div class="media-body">
                        <h6 class="mt-0">
                            <span>
                                <i class="shopping-color" data-feather="shopping-bag"></i>
                            </span>Your order ready for Ship..!
                        </h6>
                        <p class="mb-0">Lorem ipsum dolor sit amet, consectetuer.</p>
                    </div>
                </div>
            </li>
        @empty
            <li>
                No Unread Notifications
            </li>
        @endforelse

        <li class="txt-dark">
            <a href="javascript:void(0)">All</a> Notification</li>
    </ul>
</li>
