
@foreach($dataFooter as $item)
    <tr>
        @foreach($item as $key=>$value)
        @if ($loop->index < $count)
        @if ($loop->first)
        <td colspan="{{$count}}">{!! $value !!}</td>
        @endif
        @else
          <td class="text-right nowrap">{!! $value !!}</td>
        @endif
        @endforeach
    </tr>
@endforeach
