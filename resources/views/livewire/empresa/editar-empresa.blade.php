<div>
    <h2 class="text-2xl font-bold mb-4">Editar Empresa</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="atualizar">
        <div class="mb-4">
            <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome da Empresa</label>
            <input type="text" id="nome" wire:model="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('nome') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="razao_social" class="block text-gray-700 text-sm font-bold mb-2">Razão Social</label>
            <input type="text" id="razao_social" wire:model="razao_social" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('razao_social') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="cnpj" class="block text-gray-700 text-sm font-bold mb-2">CNPJ</label>
            <input type="text" id="cnpj" wire:model="cnpj" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('cnpj') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="plano" class="block text-gray-700 text-sm font-bold mb-2">Plano</label>
            <select id="plano" wire:model="plano" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="basico">Básico</option>
                <option value="premium">Premium</option>
                <option value="enterprise">Enterprise</option>
            </select>
            @error('plano') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Atualizar Empresa
            </button>
        </div>
    </form>
</div>