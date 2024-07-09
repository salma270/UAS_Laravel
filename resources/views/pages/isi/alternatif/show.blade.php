<x-layouts.app-dashboard title="{{ $title }}">

    <x-molecules.breadcrumb>
        <li aria-current="page">
            <div class="flex items-center">
                <x-atoms.svg.arrow-right />
                <a class="ml-1 text-base font-medium text-gray-900 hover:text-blue-600"
                    href="{{ route('alternatif.index') }}">Data Petshop</a>
            </div>
        </li>

        <li aria-current="page">
            <div class="flex items-center">
                <x-atoms.svg.arrow-right />
                <span class="mx-2 text-base font-medium text-gray-500">Detail Petshop</span>
            </div>
        </li>
    </x-molecules.breadcrumb>

    <div class="mx-auto my-8 w-8/12">
        <h4 class="mb-6 text-2xl font-semibold text-gray-900">Detail Petshop</h4>

        <div class="mt-8 space-y-6">
            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="kode_alternatif">
                    Kode Alternatif</label>
                <input class="field-input-slate w-full" name="kode_alternatif" type="text"
                    value="{{ $alternatif->kode_alternatif }}" @disabled(true) @readonly(true)>
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="nama_alternatif">
                    Nama Petshop</label>
                <input class="field-input-slate w-full" name="nama_alternatif" type="text"
                    value="{{ $alternatif->nama_alternatif }}" @disabled(true) @readonly(true)>
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="jam_buka">
                    Jam Buka</label>
                <input class="field-input-slate w-full" name="jam_buka" type="time"
                    value="{{ $alternatif->jam_buka }}" @disabled(true) @readonly(true)>
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="jam_tutup">
                    Jam Tutup</label>
                <input class="field-input-slate w-full" name="jam_tutup" type="time"
                    value="{{ $alternatif->jam_tutup }}" @disabled(true) @readonly(true)>
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="alamat">
                    Alamat</label>
                <input class="field-input-slate w-full" name="alamat" type="text" value="{{ $alternatif->alamat }}"
                    @disabled(true) @readonly(true)>
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="rating">
                    Rating</label>
                <input class="field-input-slate w-full" name="rating" type="text" value="{{ $alternatif->rating }}"
                    @disabled(true) @readonly(true)>
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="deskripsi">
                    Deskripsi</label>
                <input class="field-input-slate w-full" name="deskripsi" type="text"
                    value="{{ $alternatif->deskripsi }}" @disabled(true) @readonly(true)>
            </div>

            <div class="flex justify-center">
                <a href="{{ route('alternatif.index') }}">
                    <x-atoms.button.button-gray :customClass="'w-52 text-center rounded-lg px-5 py-3'" :type="'button'" :name="'Kembali'" />
                </a>
            </div>
        </div>
    </div>

</x-layouts.app-dashboard>
