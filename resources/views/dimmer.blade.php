<div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden; background: {{$color}}">
    <div class="dimmer"></div>
    <div class="panel-content">
        @if (isset($icon))<i class='{{ $icon }}'></i>@endif
        <h4><a href="{{ $button['link'] }}">{{ $title }}</a></h4>
    </div>
</div>