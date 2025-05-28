<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>MoraSoft Artisan GUI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
  .active-tab {
    background-color: #e0e7ff; /* لون bg-indigo-100 */
    color: #3730a3;            /* لون text-indigo-700 */
    font-weight: 600;          /* font-semibold */
  }

  </style>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="p-8 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-indigo-600 flex items-center gap-2">
  <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 6v6h4" />
    <circle cx="12" cy="12" r="10" />
  </svg>
  MoraSoft Artisan GUI 
  <span class="text-sm bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">v1.0</span>
</h1>
      <div class="space-x-2">
       <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
  ContactUs
</button>
      </div>
    </div>

    <!-- Tabs Layout -->
    <div class="bg-white rounded-xl shadow-md p-4 flex">
      @php
        $tabs = [
            'model' => ['label' => 'Model', 'field' => 'model_name', 'placeholder' => 'Post'],
            'controller' => ['label' => 'Controller', 'field' => 'controller_name', 'placeholder' => 'PostController'],
            'migration' => ['label' => 'Migration', 'field' => 'migration_name', 'placeholder' => 'create_posts_table'],
            'seeder' => ['label' => 'Seeder', 'field' => 'seeder_name', 'placeholder' => 'PostSeeder'],
            'validation' => ['label' => 'Validation', 'field' => 'request_name', 'placeholder' => 'StorePostRequest'],
            'event' => ['label' => 'Event', 'field' => 'event_name', 'placeholder' => 'PodcastProcessed'],
            'listener' => ['label' => 'Listener', 'field' => 'listener_name', 'placeholder' => 'SendPodcastNotification'],
            'artisan' => ['label' => 'Artisan'],
        ];
        $dangerous = ['migrate:refresh', 'migrate:fresh'];
      @endphp

      <!-- Vertical Tabs -->
   <nav class="w-60 border-r pr-4 space-y-2" id="tabs">
  @foreach ($tabs as $key => $tab)
    <button
      class="tab-button w-full text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 flex items-center gap-2"
      data-tab="{{ $key }}"
    >
      {{-- أيقونة موحّدة حسب المفتاح --}}
      @php
        $icons = [
          'model' => '<path d="M12 20h9"/><path d="M3 6h18M3 10h18M3 14h18M3 18h18"/>',
          'controller' => '<rect x="2" y="7" width="20" height="10" rx="2" ry="2" /><circle cx="12" cy="12" r="3" />',
          'migration' => '<ellipse cx="12" cy="5" rx="9" ry="3" /><path d="M3 5v14c0 1.5 4 3 9 3s9-1.5 9-3V5" />',
          'seeder' => '<path d="M12 2a10 10 0 00-3 19.47M12 2a10 10 0 013 19.47" /><path d="M9 12l3 3 3-3" />',
          'validation' => '<path d="M5 13l4 4L19 7" />',
          'event' => '<path d="M13 10V3L4 14h7v7l9-11h-7z" />', 
          'listener' => '<path d="M9 19V6h6v13h4V5a1 1 0 00-1-1H6a1 1 0 00-1 1v14h4z" />', 
          'artisan' => '<path d="M4 5h16M4 12h16M4 19h16" />', 
        ];

        $colors = [
          'model' => 'text-indigo-600',
          'controller' => 'text-green-600',
          'migration' => 'text-yellow-600',
          'seeder' => 'text-pink-600',
          'validation' => 'text-green-700',
          'event' => 'text-gray-700',
          'listener' => 'text-gray-700',
          'artisan' => 'text-gray-700',
        ];
      @endphp

      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $colors[$key] ?? 'text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {!! $icons[$key] ?? '' !!}
      </svg>

      {{ $tab['label'] }}
    </button>
  @endforeach
</nav>


      <!-- Tab Content -->
      <div class="flex-1 pl-6" id="tab-content">
        @foreach ($tabs as $key => $tab)
          <div class="tab-panel {{ $loop->first ? '' : 'hidden' }}" id="{{ $key }}">
            <form method="POST" action="{{ route('artisan.tools.execute') }}" 
                  onsubmit="disableButton(this)" 
                  {{ $key === 'artisan' ? 'id=artisan-form' : '' }} autocomplete="off" class="space-y-5">
              @csrf
              <input type="hidden" name="type" value="{{ $key }}">

              @isset($tab['field'])
                <div>
                  <label for="{{ $tab['field'] }}" class="block mb-1 font-semibold text-gray-700">
                    {{ ucfirst(str_replace('_', ' ', $tab['field'])) }}
                  </label>
                  <input
                    id="{{ $tab['field'] }}"
                    type="text"
                    name="{{ $tab['field'] }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="example: {{ $tab['placeholder'] }}"
                    required
                  />
                </div>
              @endisset

              @if($key === 'model')
                <div class="flex space-x-6">
                  <label class="inline-flex items-center space-x-2">
                    <input
                      type="checkbox"
                      name="with[]"
                      value="migration"
                      class="form-check-input h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                      id="with_migration"
                    />
                    <span class="text-gray-700">With:Migration</span>
                  </label>
                  <label class="inline-flex items-center space-x-2">
                    <input
                      type="checkbox"
                      name="with[]"
                      value="controller"
                      class="form-check-input h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                      id="with_controller"
                    />
                    <span class="text-gray-700">With:Controller</span>
                  </label>
                </div>
              @endif

             @if($key === 'controller')
                <div class="flex space-x-6">
                  <label class="inline-flex items-center space-x-2">
                    <input
                      type="checkbox"
                      name="with[]"
                      value="resource"
                      class="form-check-input h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                      id="with_resource"
                    />
                    <span class="text-gray-700">With:Resource</span>
                  </label>
                </div>
              @endif

          @if($key === 'listener')
    <div x-data="{ showInput: false }" class="flex flex-col space-y-2">
        <label class="inline-flex items-center space-x-2">
            <input
                type="checkbox"
                name="with[]"
                value="event"
                class="form-check-input h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                id="with_event"
                x-model="showInput"
            />
            <span class="text-gray-700">With:Event</span>
        </label>

        <!-- هذا هو الحقل الذي يظهر عند التحديد -->
        <input
            type="text"
            name="event_name"
            placeholder="PodcastProcessed"
            class="border rounded px-3 py-2 text-sm"
            x-show="showInput"
            x-transition
        />
    </div>
@endif


              @if($key === 'artisan')
                <div>
                  <label for="artisan_command" class="block mb-1 font-semibold text-gray-700">Artisan Command</label>
                  <select
                    id="artisan_command"
                    name="artisan_command"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                     onchange="warnIfDangerous(this)"
                  >
                    <option value="" disabled selected>-- Select Command --</option>
                    @foreach(['optimize:clear','cache:clear','config:clear','route:clear','view:clear','migrate','migrate:rollback','migrate:refresh','migrate:fresh','db:seed'] as $cmd)
                      <option value="{{ $cmd }}" class="{{ in_array($cmd, $dangerous) ? 'text-red-600' : '' }}">{{ $cmd }}</option>
                    @endforeach
                  </select>
                </div>
              @endif

            <button type="submit" id="btn-submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-md text-sm transition-colors duration-200">
  Submit
</button>

            </form>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Div منفصل للرسالة --}}
    @if(session('output'))
      @php
          $output = session('output');
          $isError = str_starts_with($output, '❌');
      @endphp
      <div class="max-w-7xl mx-auto mt-6 p-4 rounded-md border"
           style="
             white-space: pre-wrap;
             background-color: {{ $isError ? '#ffe6e6' : '#e6ffe6' }};
             border-color: {{ $isError ? '#ff4d4d' : '#4dff4d' }};
             color: {{ $isError ? '#cc0000' : '#009900' }};
           ">
        {{ $output }}
      </div>
    @endif

  </div>

<script>
  // Switch tabs
  document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
      const tab = button.dataset.tab;

      // Hide all tab panels
      document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));

      // Remove active class from all buttons
      document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active-tab'));

      // Show selected tab panel
      document.getElementById(tab).classList.remove('hidden');

      // Mark this button active
      button.classList.add('active-tab');
    });
  });

   document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('#tabs .tab-button');

    // تعيين أول تاب كـ active عند تحميل الصفحة
    if (tabs.length > 0) {
      tabs[0].classList.add('active-tab');
    }

    // عند النقر على تاب، نزيل class من الجميع ونضيفه للتاب الذي تم اختياره
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active-tab'));
        tab.classList.add('active-tab');
      });
    });
  });

  // Disable submit button on submit to prevent multiple submissions
  function disableButton(form) {
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerText = 'Processing...';
  }

    function warnIfDangerous(select) {
    const value = select.value;
    if (['migrate:refresh', 'migrate:fresh'].includes(value)) {
      alert('⚠️ تنبيه: سيتم حذف جميع البيانات، يرجى التأكد قبل المتابعة.');
    }
  }

</script>

</body>
</html>
