@extends('layouts.mr')

@push('stylesheets')
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/me/tokens.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="row pt-4">
        <div class="col">
            <h3>API Tokens</h3>
        </div>
    </div>
    <div class="row py-4">
        <div class="col">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTokenModal">
                New API Token
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="tokens" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created</th>
                        <th>Last Used</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="createTokenModal" tabindex="-1" aria-labelledby="createTokenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTokenModalLabel">API Token</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createTokenForm">
                    <div class="form-group">
                        <label for="createTokenName">Token name</label>
                        <input type="text" class="form-control form-control-lg" id="createTokenName" name="token_name" aria-describedby="createTokenHelp" />
                        <small id="createTokenHelp" class="form-text">Pick a name that helps you remember what you used this token for</small>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Create Token</button>
                    </div>
                </form>

                <div id="createTokenResult" style="display: none;" class="pt-4">
                    <p>Your API token is</p>

                    <div class="form-group">
                        <input class="form-control" type="text" readonly id="token" />
                    </div>

                    <div class="alert alert-success mt-4" role="alert">
                        <h4 class="alert-heading">Save this token</h4>
                        <p>Save the token above <em>Now.</em> It is not stored in a recoverable state. You will have
                        to recreate a new token if you lose it.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

