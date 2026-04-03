@props(['video'])

<li class="video">
    @if ($video->image_url)
        <a href="{{ $video->url }}" target="_blank">
            <img alt="{{ $video->title }}" class="video__image" src="{{ $video->image_url }}">
        </a>
    @endif
    <div class="video__info">
        <a class="video__link" href="{{ $video->url }}" target="_blank">{{ $video->title }}</a>
    </div>
</li>
