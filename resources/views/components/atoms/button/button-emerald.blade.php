@props(['customClass', 'type', 'name'])

<button
    class="{{ $customClass }} bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300"
    type="{{ $type }}" {{ $attributes }}>{{ $name }}
</button>
