<form action="{{ url('testaction') }}" method="post">
    @csrf
    <input type="text" name="text" placeholder="name">
    <input type="text" name="mincal" placeholder="min">
    <input type="text" name="maxcal" placeholder="max">
    <button type="submit">submit</button> 
</form>


@if(@$content != '' && count($content) > 0)
<ul>
    @foreach($content as $c)
        @if(strpos($c,"1") === 0 ||
        strpos($c,"2") === 0 ||
        strpos($c,"3") === 0 ||
        strpos($c,"4") === 0 ||
        strpos($c,"5") === 0 ||
        strpos($c,"6") === 0 ||
        strpos($c,"7") === 0 ||
        strpos($c,"8") === 0 ||
        strpos($c,"9") === 0)
            <li>{{ $c }}</li>
        @endif
    @endforeach
</ul>
@endif