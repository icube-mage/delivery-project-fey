<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-violet-700 active:bg-violet-700 focus:outline-none focus:border-violet-700 focus:ring ring-violet-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
