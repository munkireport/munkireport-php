@extends('layouts.mr')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="jumbotron">
                    <h1 class="display-4">Installing the agent</h1>
                    <p class="lead">Follow these steps to add a new client</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm mt-4">
                <p class="text-secondary">Step 1</p><p class="lead">Deploy Python 3.x to your Macs</p>
                <p>
                    Deploy Python 3.x to your macs using your desired deployment mechanism. This could be Munki, your MDM, or some other management system.
                </p>
                <p>
                    The Python 3.x distribution recommended for MunkiReport is <a href="https://github.com/macadmins/python/releases">macadmins-python</a>.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm mt-4">
                <p class="text-secondary">Step 2</p><p class="lead">Choose a client deployment type: script or package</p>
                <p>
                    You can run the bash installer directly from curl using the following command.
                    <strong>NOTE</strong> it is good security practise to review the <a href={{ url('/install') }}>contents of the script</a>.

                    <pre><code>
                        $ /bin/bash -c "$(curl '{{ url('/install') }}')"
                    </code></pre>

                    More commonly, the distribution method chosen is via an installer package. The bash script can also generate
                    installer packages using the <code>-i</code> flag:

                    <pre><code>
                        $ /bin/bash -c "$(curl '{{ url('/install') }}')" bash -i ~/Desktop
                    </code></pre>
                </p>
                <p>
                    If you are using <a href="">Autopkg</a>, then there is a munkireport recipe in the munkireport-recipes repo.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm mt-4">
                <p class="text-secondary">Step 3</p><p class="lead">Distribute client configuration</p>
                <p>
                    MunkiReport will be installed on the client with some sane defaults, but if you need to tweak configuration for
                    your environment, you will need to provide an updated configuration file via Profile (<code>MunkiReport pref domain</code>),
                    <code>defaults write</code>, or <code>/Library/Preferences/MunkiReport.plist</code> file.
                </p>
                <p>
                    You will need special configuration if you are using the machine groups functionality, or if your outgoing request headers need
                    to be modified to contact MunkiReport.
                </p>
            </div>
        </div>
    </div>
@endsection
