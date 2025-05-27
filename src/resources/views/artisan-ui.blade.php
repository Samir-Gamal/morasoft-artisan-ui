<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MoraSoft Artisan GUI</title>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
       
        body { background: #f8fafc; }
        .container { max-width: 700px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); margin-bottom: 50px; }
        h2 { font-weight: 700; margin-bottom: 30px; color: #343a40; text-align: center; user-select: none; }
        .nav-tabs .nav-link { font-weight: 600; border: none; margin-left: 10px; border-radius: 10px 10px 0 0; }
        .nav-tabs .nav-link.active { background: #0d6efd; color: white; box-shadow: 0 4px 12px rgba(13,110,253,0.4); }
        .form-control, .form-select { border-radius: 8px; box-shadow: inset 0 1px 4px rgba(0,0,0,0.1); }
        .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 8px rgba(13,110,253,0.25); }
        .btn { font-weight: 600; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.12); }
        .btn:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .result-box { margin-top: 30px; padding: 20px; border-radius: 12px; font-family: monospace; white-space: pre-wrap; }
      
    </style>
</head>
<body>
<div class="container">
    <h2>🛠️ MoraSoft Artisan GUI</h2>

    @php
        $tabs = [
            'model' => ['label' => 'Model', 'field' => 'model_name', 'placeholder' => 'Post'],
            'controller' => ['label' => 'Controller', 'field' => 'controller_name', 'placeholder' => 'PostController'],
            'migration' => ['label' => 'Migration', 'field' => 'migration_name', 'placeholder' => 'create_posts_table'],
            'seeder' => ['label' => 'Seeder', 'field' => 'seeder_name', 'placeholder' => 'PostSeeder'],
            'validation' => ['label' => 'Validation', 'field' => 'request_name', 'placeholder' => 'StorePostRequest'],
            'artisan' => ['label' => 'Artisan'],
        ];
        $dangerous = ['migrate:refresh', 'migrate:fresh'];
    @endphp

    <ul class="nav nav-tabs" id="tabs" role="tablist">
        @foreach($tabs as $key => $tab)
            <li class="nav-item">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#{{ $key }}">{{ $tab['label'] }}</button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content mt-4">
        @foreach($tabs as $key => $tab)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}">
                <form method="POST" action="{{ route('artisan.tools.execute') }}"   onsubmit="disableButton(this)" {{ $key === 'artisan' ? 'id=artisan-form' : '' }} autocomplete="off">
                    @csrf
                    <input type="hidden" name="type" value="{{ $key }}">

                    @isset($tab['field'])
                        <div class="mb-3">
                            <label class="form-label">{{ $tab['field'] }}</label>
                            <input type="text" name="{{ $tab['field'] }}" class="form-control" placeholder="example: {{ $tab['placeholder'] }}" required>
                        </div>
                    @endisset

                    @if($key === 'model')
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="with[]" value="migration" id="with_migration">
                            <label class="form-check-label" for="with_migration">with:migration</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="with[]" value="controller" id="with_controller">
                            <label class="form-check-label" for="with_controller">with:controller</label>
                        </div>
                    @endif

                    @if($key === 'artisan')
                        <div class="mb-3">
                            <label for="artisan_command" class="form-label">artisan_command</label>
                            <select name="artisan_command" id="artisan_command" class="form-select" required>
                                <option value="" disabled selected>-- Selected Command --</option>
                                @foreach(['optimize:clear','cache:clear','config:clear','route:clear','view:clear','migrate','migrate:rollback','migrate:refresh','migrate:fresh','db:seed'] as $cmd)
                                    <option value="{{ $cmd }}" class="{{ in_array($cmd, $dangerous) ? 'text-danger' : '' }}">{{ $cmd }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button type="submit" id='btn-submit' class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        @endforeach
    </div><br>

    @if(session('output'))
        @php
            $output = session('output');
            $isError = str_contains($output, '❌') || str_contains($output, 'Error') || str_contains($output, 'Exception') || str_contains($output, 'SQL');
        @endphp
        <div class="alert {{ $isError ? 'alert-danger' : 'alert-success' }}">
            <pre>{!! nl2br(e($output)) !!}</pre>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('artisan-form')?.addEventListener('submit', function (e) {
        const command = document.getElementById('artisan_command').value;
        const dangerousCommands = @json($dangerous);
        if (dangerousCommands.includes(command)) {
            const confirmed = confirm("⚠️ تحذير: هذا الأمر سيقوم بحذف جميع البيانات في الجداول. هل أنت متأكد أنك تريد المتابعة؟");
            if (!confirmed) e.preventDefault();
        }
    });

    function disableButton(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = "please wait processing...<span class='spinner-border spinner-border-sm align-middle ms-2'></span>";
    }
}
</script>
</body>
</html>
