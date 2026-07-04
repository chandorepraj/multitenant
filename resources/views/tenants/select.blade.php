@include('layouts.header')
  <body>
   

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
    <div class="container">
    <form method="POST" action="{{ route('users.tenant') }}">
        @csrf
        <select name="tenant_id" id="tenant_id">
        @foreach ($tenants as $tenant)
            <option value="{{ $tenant->id}}">{{ $tenant->name }}</option>
        @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Select Organization</button>

    </form>
    </div>
  </body>
</html>

