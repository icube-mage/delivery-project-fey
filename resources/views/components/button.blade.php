<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-sky-600 enabled:hover:bg-sky-500 active:bg-sky-500 focus:border-sky-500 justify-center tracking-widest focus:outline-none focus:ring ring-sky-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
