<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-pink-600 hover:bg-pink-500 active:bg-pink-500 focus:border-pink-500 justify-center tracking-widest focus:outline-none focus:ring ring-pink-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
