<table class="wp-list-table widefat fixed striped posts">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
    </tr>
    </thead>
    @foreach(get_post_meta($transaction->ID) as $key => $meta)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ $meta[0] }}</td>
        </tr>
    @endforeach
</table>
