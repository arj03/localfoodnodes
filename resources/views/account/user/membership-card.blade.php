@if ($user->isMember())
    <!-- Paid -->
    <div class="card">
        <div class="card-header">{{ trans('admin/user.membership_paid_title') }}</div>
        <div class="card-body">
            <p>{{ trans('admin/user.membership_paid', [
                'date' => $user->getLatestMembershipPayment()->getDateOneYearForward()->format('Y-m-d'),
                'days' => $user->getLatestMembershipPayment()->expiresInDays()
            ]) }}</p>

            <p><a class="btn btn-success" href="/membership">{{ trans('admin/user.membership_paid_link') }}</a></p>
        </div>
        <div class="card-footer">
            <a href="/account/user/membership">{{ trans('admin/user.renew_membership') }}</a>
        </div>
    </div>
@else
    <!-- Unpaid -->
    <div class="card">
        <div class="card-header">{{ trans('admin/user.membership_unpaid_title') }}</div>
        <div class="card-body">
            <p>{{ trans('admin/user.membership_unpaid') }}</p>
            <p><a class="btn btn-success" href="/membership">{{ trans('admin/user.membership_unpaid_link') }}</a></p>
        </div>
        <div class="card-footer">
            <a href="/account/user/membership">{{ trans('admin/user.become_a_member') }}</a>
        </div>
    </div>
@endif
