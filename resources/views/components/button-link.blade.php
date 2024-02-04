<a {{ $attributes->merge(['href' => '#', 'class' => "text-white bg-dark-blue-300 hover:bg-dark-blue-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-largest text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"]) }}>
    {{ $slot }}
</a>