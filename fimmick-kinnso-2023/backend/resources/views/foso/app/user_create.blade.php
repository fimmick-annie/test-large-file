@extends('foso.layouts.default')

@section('page_title', 'App User Create')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.app.user.html") }}'>App</a></li>
<li><i class='fa fa-angle-right'></i> User</li>
@endsection

@section('content')

<form id="form" name="form">
    @csrf

    <div class="card">
        <div class='card-body'>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Name</label>
                        <input class="form-control" type="text" value="" id="name" name="name">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Email</label>
                        <input class="form-control" type="text" value="" id="email" name="email">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Password</label>
                        <input class="form-control" type="password" value="" id="password" name="password">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Roles</label>
                        <input class="form-control" type="text" value="normal_user" id="roles" name="roles">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Api Token</label>
                        <input class="form-control" type="text" value="" id="api_token" name="api_token" readonly>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Token Expiry At</label>
                        <input class="form-control" type="text" value="" id="token_expiry_at" name="token_expiry_at" readonly>
                    </fieldset>
                </div>
            </div>
            <button type="button" class="btn btn-danger" id="saveButton">Save</button>

        </div>
    </div>
</form>




<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>


<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script>
    document.querySelector('#saveButton').addEventListener('click', (e) => {
        let inputs = document.querySelectorAll('input');
        let json = {};

        inputs.forEach(el => {
            json[el.name] = el.value;
        })

        fetch(`${location.href}`, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(json),
            })
            .then(response => response.json())
            .then(json => {

                alert(json.message);
                if (json.ok) {
                    history.back();
                }

            })
    })
</script>


@endsection