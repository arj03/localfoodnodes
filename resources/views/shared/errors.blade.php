<div class="master-alerts">
    @if (Session::has('message'))
        @foreach (Session::get('message') as $index => $message)
            <div class="alert alert-success" role="alert" data-dismiss="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ $message }}
            </div>
        @endforeach
    @endif

    @if (Session::has('error'))
        @foreach (Session::get('error') as $index => $error)
            <div class="alert alert-danger" role="alert" data-dismiss="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ $error }}
            </div>
        @endforeach
    @endif
</div>

<script>
    $(function () {
        function showNotification() {
            setTimeout(function() {
                $($('.master-alerts .alert').get().reverse()).each(function(index) {
                    $(this).delay(300 * index).fadeOut();
                });
            }, 10000);
        }

        showNotification();

        $(document).on('notification', function(event, text) {
            if (!text && event.detail) {
                text = event.detail;
            }

            var alert = '<div class="alert alert-success" role="alert" data-dismiss="alert">' + text + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';

            $('.master-alerts').append(alert);
            showNotification();
        });
    });
</script>
