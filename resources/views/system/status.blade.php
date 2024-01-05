@extends('layouts.mr')

@section('content')
    <div class="container">
        <div class="row pt-4">
            <h3 class="display-4" data-i18n="system.status">MunkiReport System Status</h3>
        </div>

        <div class="row pt-4">
            <div id="mr-phpinfo" class="col">
                <h4 data-i18n="php.php">PHP</h4>
                <table class="table table-striped">
                    <tr>
                        <th data-i18n="php.version">PHP Version</th>
                        <td>{{ $php['php.version'] }}</td>
                    </tr>
                    <tr>
                        <th data-i18n="php.uname">Operating System</th>
                        <td>{{ $php['php.uname'] }}</td>
                    </tr>
                    <tr>
                        <th data-i18n="php.ini_loaded_file">INI File</th>
                        <td>{{ $php['php.ini_loaded_file'] }}</td>
                    </tr>
                    <tr>
                        <th data-i18n="php.ini_scanned_files">Additional scanned INI files</th>
                        <td>{{ $php['php.ini_scanned_files'] }}</td>
                    </tr>
                    <tr>
                        <th data-i18n="php.memory_peak_usage">Memory Peak Usage</th>
                        <td>{{ $php['php.memory_peak_usage'] }}</td>
                    </tr>
                </table>

                <a class="btn btn-info" data-i18n="php.moreinfo" href="/system/php_info">Extended PHP info</a>
            </div>

            <div id="mr-db" class="col">
                <h4 data-i18n="database">Database</h4>
                <table class="table table-striped">
                    @foreach ($connection as $name => $value)
                        <tr>
                            <th data-i18n="{{ $name }}">{{ $name }}</th>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>  <!-- /container -->
@endsection
