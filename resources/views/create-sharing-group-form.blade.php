<x-base-layout>
    <x-base-body>
        <div class="m-10">
            <H1 class="text-xl">Buat Grup Sharing Baru</H1>
            @if ($errors->any())
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
            <form action="/sharing-group/new" method="post">
                @csrf
                <div class="my-5">
                    <label for="platform">Platform</label>
                    <?php $platforms = array('Netflix', 'Disney+', 'Spotify', 'Youtube', 'Vidio'); ?>
                    <select name="platform" id="platform" class="w-full" onchange="changePacket()">
                        @foreach($platforms as $platform)
                        <option value="{{ $platform }}">
                            {{ $platform }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="my-5">
                    <label for="packet">Paket</label>
                    <select name="packet" id="packet" class="w-full"></select>
                </div>
                <div class="my-5">
                    <label for="duration">Durasi</label>
                    <input type="number" name="duration" id="duration" min=1 max=100 class="w-full" step=1 value=1>
                </div>
                <div class="my-5">
                    <label for="platform">Harga</label>
                    <input type="number" name="price" id="price" min=0 max=1000000 class="w-full" value=50000>
                </div>
                <div class="my-5">
                    <label for="quota">Kuota</label>
                    <input type="number" name="quota" id="quota" min=1 max=100 class="w-full" step=1 value=1>
                </div>
                <button type="submit" class="px-3 py-2 outline outline-1 rounded hover:bg-slate-200 hover:duration-700">
                    Buat Grup Sharing
                </button>
            </form>
        </div>
        <script>
            const packet = document.getElementById('packet')
            const platform = document.getElementById('platform')
            const packets = {
                'Netflix': ['Ponsel', 'Dasar', 'Standar', 'Premium'],
                'Disney+': ['Standar'],
                'Spotify': ['Duo', 'Family'],
                'Youtube': ['Family'],
                'Vidio': ['Gold', 'Silver'] 
            }

            var options_str = ''

            console.log(packets.Netflix)

            packets.Netflix.forEach(function(packet) {
                options_str += '<option value="' + packet + '">' + packet + '</option>'
            })

            packet.innerHTML = options_str;
            var options_str = ''

            const changePacket = () => {
                $platform_selected = platform.options[platform.selectedIndex].value
                packets[$platform_selected].forEach(function(packet) {
                    options_str += '<option value="' + packet + '">' + packet + '</option>'
                })
                packet.innerHTML = options_str;
                options_str = ''
            }
        </script>
    </x-base-body>
</x-base-layout>