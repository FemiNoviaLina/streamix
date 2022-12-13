<<<<<<< HEAD
    <div class="flex">
        <div class="flex-1 border-b-2 border-pink-700">
            <a class="text-center block border-r border-gray-100 py-2 px-4 hover:bg-gray-100" href="#">Netflix</a>
        </div>
        <div class="flex-1 border-b" >
            <a class="text-center block border-r border-gray-100 hover:border-gray-200 hover:bg-gray-200 py-2 px-4" href="#">Disney+</a>
        </div>
        <div class="flex-1 border-b">
            <a class="text-center block border-r border-gray-100 hover:border-gray-200 hover:bg-gray-200 py-2 px-4" href="#">Spotify</a>
            </div>
        <div class="flex-1 border-b">
            <a class="text-center block border-r border-gray-100 hover:border-gray-200 hover:bg-gray-200 py-2 px-4" href="#">Youtube</a>
        </div>
        <div class="flex-1 border-b">
            <a class="text-center block border-r border-gray-100 hover:border-gray-200 hover:bg-gray-200 py-2 px-4" href="#">Vidio</a>
        </div>
=======
<?php 
    $platforms=array('Netflix', 'Disney+', 'Spotify', 'Youtube', 'Vidio');
    $selected = request()->get('platform') ? request()->get('platform') : 'Netflix';
?>
<div class="flex">
    @foreach($platforms as $platform)
    @if($platform == $selected)
    <div class="flex-1 border-b-2 border-purple-400">
        <a class="text-center block border-r border-gray-100 py-2 px-4 hover:bg-gray-100" href="#">{{ $platform }}</a>
>>>>>>> 64069e0f14c63590eddeab939968b7d2c5e90558
    </div>
    @else
    <div class="flex-1 border-b" >
        <a class="text-center block border-r border-gray-100 hover:border-gray-200 hover:bg-gray-200 py-2 px-4" href="{{ request()->fullUrlWithQuery(['platform' => $platform]) }}">{{ $platform }}</a>
    </div>
    @endif
    @endforeach
</div>
