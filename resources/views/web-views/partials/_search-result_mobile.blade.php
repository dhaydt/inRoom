<style>
    .list-group-item li, a {
        color: {{$web_config['primary_color']}};
    }

    .list-group-item li, a:hover {
        color: {{$web_config['secondary_color']}};
    }
</style>
<ul class="list-group list-group-flush">
    @foreach($products as $i)
        <li class="list-group-item" onclick="$('.search_form_mobile').submit()">
            <a href="javascript:"
            {{-- onmouseover="$('.search-bar-input-mobile').val('{{$i['kost']['name']}}');$('.search-bar-input').val('{{$i['kost']['name']}}');" --}}
            >
                {{$i['kost']['name']}}, {{ $i['kost']['note_address'] }} {{ $i['kost']['city'] }} - {{ $i['kost']['province'] }}
            </a>
        </li>
    @endforeach
</ul>