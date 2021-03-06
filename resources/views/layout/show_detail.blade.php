@if(Auth::check())
    <a href="/logout">Logout</a>

@section("table")
@if($column != null)
    <table class="table table-striped" style="padding:2% 2% 0 0;">
        <thead>
        <tr>
            @foreach($column as $col)
                <th scope="col">{{$col}}</th>
            @endforeach
            <th scope="col">Detail</th>
        </tr>
        </thead>
        @endif
        @if($items != null)
            <tbody>
        @foreach($items as $item)
            <tr>
                @foreach($column as $col)
                    <td>{{$item[$col]}}</td>
                @endforeach
                <td><a href={{$detail_api}}{{$item[$id]}}>Detail</a> </td>

            </tr>
        @endforeach
        </tbody>
            @endif
    </table>
@show
@else
    <a href="/login">Login</a>
    @endif
