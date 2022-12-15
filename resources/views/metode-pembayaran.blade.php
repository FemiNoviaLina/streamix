<x-base-layout>
    <x-base-body>
        <div class="m-10">
            <div class="text-2xl my-5">
                <h1>Pilih Metode Pembayaran</h1>
            </div>
            <form action="{{ route('payment', ['id' => $id ]) }}" method="post">
                @csrf
                <?php
                    $methods = array(
                        'BCA Transfer' => 'bca.png',
                        'Mandiri Transfer' => 'mandiri.png',
                        'BRI Transfer' => 'bri.png',
                        'BNI Transfer' => 'bni.png',
                        'Shopeepay Transfer' => 'shopee.png',
                        'Gopay Transfer' => 'gopay.png',
                    )
                ?>
                <div>
                    @foreach($methods as $key => $value)
                    <div class="flex items-center pl-4 rounded border border-gray-200 my-2">
                        <input id="bordered-radio-1" type="radio" value="{{ $key }}" name="method" class="w-4 h-4 mr-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                        <img src="{{ asset('images/'.$value) }}" alt="">
                        <label for="bordered-radio-1" class="py-4 ml-2 w-full text-sm font-medium text-gray-900">{{ $key }}</label>
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="px-3 py-2 outline outline-1 rounded hover:bg-slate-200 hover:duration-700">
                        Pilih Metode Pembayaran
                </button>
            </form>
        </div>
    </x-base-body>
</x-base-layout>