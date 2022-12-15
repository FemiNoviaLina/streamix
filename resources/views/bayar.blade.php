<x-base-layout>
    <x-base-body>
        <div class="w-2/3 mx-40 my-32">
            <div class="bg-gray-200 rounded-lg px-5 py-5">
                <div class="p-5">
                    <p class="font-medium">Untuk bergabung grup anda harus menyelesaikan pembayaran terlebih dahulu</p>
                </div>
                <div class="bg-gray-100 rounded-lg ml-5 mr-5 mb-5 pb-5">
                    <div class="flex">
                        <div class="w-full inline-flex justify-center gap-10 ">
                            <div class="py-5">
                                <div class="space-x-2 items-center text-center">
                                    <h1>Paket</h1>
                                </div>
                                <div class="items-center text-center px-5 pt-5">
                                    <h1>Paket {{ $sharingGroup->packet }}</h1>
                                </div>                       
                            </div>
                            <div class="py-5">
                                <div class="space-x-2 items-center text-center px-5">
                                    <h1>Berlaku</h1>
                                </div>
                                <div class="space-x-2 items-center text-center px-5 pt-5">
                                    <h1>{{ $sharingGroup->duration }} bulan</h1>
                                </div>
                            </div>
                            <div class="py-5">
                                <div class="space-x-2 items-center text-center px-5">
                                    <h1>Harga</h1>
                                </div>
                                <div class="space-x-2 items-center text-center px-5 pt-5">
                                    <h1>Rp {{ $sharingGroup->price }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded-lg bg-gray-200 text-center mx-5">
                        <a href="{{ route('pick-payment', ['id' => $sharingGroup->id]) }}">Bayar</a>
                    </div>
                </div>
            </div>
        </div>
    </x-base-body>
</x-base-layout>