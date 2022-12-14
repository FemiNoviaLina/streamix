<x-base-layout>
    <x-base-body>
        <div class="w-2/3 mx-40 my-32">
            <div class="bg-gray-200 rounded-lg px-5">
                <div class="p-5">
                    <p class="text-xl font-bold bg-white rounded-lg text-center">Rp {{ $sharingGroup[0]->price}} /bulan</p>
                </div>
                <div class="px-5 mb-5">
                    <p class="font-medium">Paket {{ $sharingGroup[0]->packet }}</p>
                </div>
                <div class="flex">
                <div class="w-2/3 inline-flex gap-10 bg-gray-100 ml-5 mb-5 px-10">
                    <div class="p-5">
                        <div class="space-x-2 items-center text-center px-5 pt-5">
                            <h1>Owner</h1>
                        </div>
                        <div class="inline-flex space-x-2 items-center text-center p-5">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.75 7.5C13.75 8.49456 13.3549 9.44839 12.6517 10.1517C11.9484 10.8549 10.9946 11.25 10 11.25C9.00544 11.25 8.05161 10.8549 7.34835 10.1517C6.64509 9.44839 6.25 8.49456 6.25 7.5C6.25 6.50544 6.64509 5.55161 7.34835 4.84835C8.05161 4.14509 9.00544 3.75 10 3.75C10.9946 3.75 11.9484 4.14509 12.6517 4.84835C13.3549 5.55161 13.75 6.50544 13.75 7.5Z" fill="black"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 10C0 7.34784 1.05357 4.8043 2.92893 2.92893C4.8043 1.05357 7.34784 0 10 0C12.6522 0 15.1957 1.05357 17.0711 2.92893C18.9464 4.8043 20 7.34784 20 10C20 12.6522 18.9464 15.1957 17.0711 17.0711C15.1957 18.9464 12.6522 20 10 20C7.34784 20 4.8043 18.9464 2.92893 17.0711C1.05357 15.1957 0 12.6522 0 10ZM10 1.25C8.35222 1.25009 6.73796 1.71545 5.343 2.59253C3.94805 3.46962 2.8291 4.72276 2.11496 6.20774C1.40081 7.69272 1.12048 9.34917 1.30625 10.9864C1.49201 12.6237 2.13632 14.1753 3.165 15.4625C4.0525 14.0325 6.00625 12.5 10 12.5C13.9937 12.5 15.9463 14.0312 16.835 15.4625C17.8637 14.1753 18.508 12.6237 18.6938 10.9864C18.8795 9.34917 18.5992 7.69272 17.885 6.20774C17.1709 4.72276 16.052 3.46962 14.657 2.59253C13.262 1.71545 11.6478 1.25009 10 1.25Z" fill="black"/>
                            </svg>
                            <span>{{ $sharingGroup[0]->name}}</span>  
                        </div>                        
                    </div>
                    <div class="p-5">
                        <div class="space-x-2 items-center text-center px-5 pt-5">
                            <h1>Kuota</h1>
                        </div>
                        <div class="space-x-2 items-center text-center pt-2">

                            <h1 class="text-3xl">{{ isset($sharingGroup[0]->member) ? count($sharingGroup) : 0 }}/{{ $sharingGroup[0]->quota }}</h1>
                        </div>
                    </div>
                </div>
                <?php
                    $joined = false;
                    foreach($sharingGroup as $sg) {
                        if($sg->member == auth()->user()->id) {
                            $joined = true;
                            break;
                        }
                    }
                ?>
                @if($sharingGroup[0]->owner_id != auth()->user()->id and !$joined)
                <a class="w-36 text-center bg-gray-100 ml-5 mb-5 py-16" href="{{ route('join-temporary', ['id' => ($sharingGroup[0]->id)]) }}">
                    <h1>Gabung</h1>
                </a>
                @endif
                </div>
            </div>
        </div>
    </x-base-body>
</x-base-layout>