<div class="wrap acf-settings-wrap">

    <h1>{{ $title }}</h1>

    @if(empty($logs))
        <p>No logs yet.</p>
    @else
        <table class="wp-list-table widefat fixed striped posts">
            @foreach($logs as $log)
                <tr>
                    <td>
                       <pre style="white-space: pre-line;">{{ $log }}</pre>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

</div>