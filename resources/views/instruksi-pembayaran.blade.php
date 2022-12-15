<x-base-layout>
    <x-base-body>
        <div class="m-10">
            <div class="flex gap-96">
                <div class="text-2xl">
                    <h1>Instruksi Pembayaran</h1>
                </div>
                <div class="ml-20">
                    <div>
                        <h1>Order Id : {{ $transaction->transaction_id }}</h1>
                    </div>
                </div>
            </div>
            <div class="mt-4 mb-20 px-20 py-10 m-auto">
                <ol class="relative border-l-2 border-pink-700 ">                  
                    <li class="mb-10 ml-4">
                        <div class="absolute w-8 h-8 bg-pink-700 rounded-full text-center font-bold text-white pt-1 -left-4 border border-pink-700 bg-pink-700">1</div>
                        <h3 class="text-xl font-semibold ml-4 mb-4">Bayar Sebelum :</h3>
                        <p class="text-base font-normal ml-4">{{ $transaction->payment_expiry_time }}</p>
                    </li>
                    <li class="mb-10 ml-4">
                    <div class="absolute w-8 h-8 bg-pink-700 rounded-full text-center font-bold text-white pt-1 -left-4 border border-pink-700 bg-pink-700">2</div>
                        <h3 class="text-xl font-semibold ml-4 mb-4">Tujuan Transfer</h3>
                        <div class="grid gap-5 ml-4">
                            <div class="text-lg">
                                <h1>{{ $transaction->payment_method }}</h1>
                            </div>
                            <div class="flex gap-96">
                                <div class="text-m">
                                    <h1>Nomor Virtual Account</h1>
                                </div>
                                <div class="text-m ml-20">
                                    <h1>{{ $transaction->virtual_account }}</h1>
                                </div>
                            </div>
                            <div class="flex gap-96">
                                <div class="text-m">
                                    <h1>Nama</h1>
                                </div>
                                <div class="text-m ml-32">
                                    <h1>business.ly</h1>
                                </div>
                            </div>
                            <div class="border-b-2">
                                <h1></h1>
                            </div>
                            <div class="flex gap-96">
                                <div class="text-lg">
                                    <h1>Total Pembayaran</h1>
                                </div>
                                <div class="text-lg ml-24">
                                    <h1>Rp {{ $transaction->total_price }}</h1>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mb-10 ml-4">
                        <div class="absolute w-8 h-8 bg-pink-700 rounded-full text-center font-bold text-white pt-1 -left-4 border border-pink-700 bg-pink-700">3</div>
                        <h3 class="mb-4 text-xl font-semibold ml-4">Sudah Bayar?</h3>
                        <a class="border-2 rounded-lg text-base font-normal ml-4 p-2" href="/succsess-join">Saya Sudah Bayar</a>
                    </li>
                </ol>
            </div>
        </div>
    </x-base-body>
</x-base-layout>