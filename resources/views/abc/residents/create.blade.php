<form action="{{ route('abc.residents.store') }}" method="POST" class="space-y-8">
    @csrf
    @include('abc.residents._form')

    <div class="flex justify-end">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow transition duration-150 ease-in-out">
            Save Resident
        </button>
    </div>
</form>
