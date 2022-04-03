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
        <li class="list-group-item" onclick="$('.search_form').submit()">
            <a href="javascript:" onmouseover="$('.search-bar-input-mobile').val('{{$i['kost']['name']}}');$('.search-bar-input').val('{{$i['kost']['name']}}');">
                {{$i['kost']['name']}}
            </a>
        </li>
    @endforeach
</ul>
