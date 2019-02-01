
@foreach($dataFooter as $item)
    <tr>
        @foreach($item as $key=>$value)
            <td
                @if(
                $key !== 'login_id' &&
                $key !== 'Name'
                )class="text-right nowrap"
                @endif>{!! $value !!}</td>
        @endforeach
    </tr>
@endforeach
