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
                <span class="mx-2 text-base font-medium text-gray-500">Ubah Petshop</span>
            </div>
        </li>
    </x-molecules.breadcrumb>

    <div class="mx-auto my-8 w-8/12">
        <h4 class="mb-6 text-2xl font-semibold text-gray-900">Ubah Petshop</h4>

        <form class="mt-8 space-y-6" action="{{ route('alternatif.update', $alternatif->id_alternatif) }}"
            method="POST">
            @method('PUT')
            @csrf

            <div class="hidden">
                <label class="mb-2 block text-base font-medium text-gray-900" for="kode_alternatif">
                    Kode Alternatif</label>
                <input class="@error('kode_alternatif') border-red-500 @enderror field-input-slate w-full"
                    name="kode_alternatif" type="text" value="{{ $alternatif->kode_alternatif }}" required
                    @readonly(true)>

                @error('kode_alternatif')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="nama_alternatif">
                    Nama Petshop</label>
                <input class="@error('nama_alternatif') border-red-500 @enderror field-input-slate w-full" name="nama_alternatif"
                    type="text" value="{{ $alternatif->nama_alternatif }}" required>

                @error('nama_alternatif')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="jam_buka">
                    Jam Buka</label>
                <input class="@error('jam_buka') border-red-500 @enderror field-input-slate w-full"
                    name="jam_buka" type="time" value="{{ $alternatif->jam_buka }}" required>

                @error('jam_buka')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="jam_tutup">
                    Jam Tutup</label>
                <input class="@error('jam_tutup') border-red-500 @enderror field-input-slate w-full"
                    name="jam_tutup" type="time" value="{{ $alternatif->jam_tutup }}" required>

                @error('jam_tutup')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="alamat">
                    Alamat</label>
                <input class="@error('alamat') border-red-500 @enderror field-input-slate w-full" name="alamat"
                    type="text" value="{{ $alternatif->alamat }}" required>

                @error('alamat')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="rating">
                    Rating</label>
                <input class="@error('rating') border-red-500 @enderror field-input-slate w-full" name="rating"
                    type="text" value="{{ $alternatif->rating }}" required>

                @error('rating')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-base font-medium text-gray-900" for="deskripsi">
                    Deskripsi</label>
                <input class="@error('deskripsi') border-red-500 @enderror field-input-slate w-full" name="deskripsi"
                    type="text" value="{{ $alternatif->deskripsi }}" required>

                @error('deskripsi')
                    <p class="invalid-feedback">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-row gap-4">
                <a href="{{ route('alternatif.index') }}">
                    <x-atoms.button.button-gray :customClass="'w-52 text-center rounded-lg px-5 py-3'" :type="'button'" :name="'Kembali'" />
                </a>
                <x-atoms.button.button-primary :customClass="'w-full text-center rounded-lg px-5 py-3'" :type="'submit'" :name="'Ubah'" />
            </div>
        </form>
    </div>

</x-layouts.app-dashboard>
