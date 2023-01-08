<x-base-layout>
    <x-base-body>
        <div id="last-incomes" class="m-10">
            <div class="text-3xl my-10">
                <h1>Grup Sharing Anda</h1>
            </div>
            <div id="stats" class="w-1/2 grid gap-4">
                @foreach($groupSharing as $gs)
                <a class="bg-pink-700 rounded-lg" href="#">
                    <div class="flex gap-80">
                        <div class="p-5 text-3xl text-white">
                            <h1>{{ $gs->platform }}</h1>
                        </div>
                        <div class="p-5 w-1/2">
                            <p class="text-xl text-pink-700 font-bold bg-white rounded-lg text-center p-2">Member</p>
                        </div>
                    </div>
                    <div class="inline-flex gap-80 text-white">
                        <div class="px-5 py-5">
                            <p class="text-xl">Paket {{ $gs->packet }}</p>
                        </div>
                        <div class="space-x-2 items-center text-center p-5">
                            <span>Femi Novia Lina</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </x-base-body>
</x-base-layout>
