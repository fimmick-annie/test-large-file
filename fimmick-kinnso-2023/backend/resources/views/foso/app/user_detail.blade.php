@extends('foso.layouts.default')

@section('page_title', 'App User Detail')

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
                        <label for="">Id</label>
                        <input class="form-control" type="text" value="{{ empty($record->id)?'':$record->id }}" id="id" name="id" readonly>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <fieldset class="form-group">
                        <label for="">Create Time</label>
                        <input class="form-control" type="text" value="{{ empty($record->created_at)?'':$record->created_at }}" id="created_at" name="created_at" disabled>
                    </fieldset>
                </div>
                <div class="col-lg-4">
                    <fieldset class="form-group">
                        <label for="">Update Time</label>
                        <input class="form-control" type="text" value="{{ empty($record->updated_at)?'':$record->updated_at }}" id="updated_at" name="updated_at" disabled>
                    </fieldset>
                </div>
                <div class="col-lg-4">
                    <fieldset class="form-group">
                        <label for="">Delete Time</label>
                        <input class="form-control" type="text" value="{{ empty($record->deleted_at)?'':$record->deleted_at }}" id="deleted_at" name="deleted_at" disabled>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Name</label>
                        <input class="form-control" type="text" value="{{ empty($record->name)?'':$record->name }}" id="name" name="name">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Email</label>
                        <input class="form-control" type="text" value="{{ empty($record->email)?'':$record->email }}" id="email" name="email" readonly>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Password</label>
                        <input class="form-control" type="text" value="{{ empty($record->password)?'':$record->password }}" id="" name="" readonly>
                    </fieldset>
                </div>
                <div class="col-lg-auto">
                    <fieldset class="form-group">
                        <label class='d-none d-lg-block'>&nbsp;</label>
                        <input style="width:auto;" class="form-control " type="button" value="Change Password" id="change_password" name="change_password" data-toggle="modal" data-target="#exampleModal">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Roles</label>
                        <input class="form-control" type="text" value="{{ empty($record->roles)?'':$record->roles }}" id="roles" name="roles">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Api Token</label>
                        <input class="form-control" type="text" value="{{ empty($record->api_token)?'':$record->api_token }}" id="api_token" name="api_token" readonly>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <fieldset class="form-group">
                        <label for="">Token Expiry At</label>
                        <input class="form-control" type="text" value="{{ empty($record->token_expiry_at)?'':$record->token_expiry_at }}" id="token_expiry_at" name="token_expiry_at">
                    </fieldset>
                </div>
            </div>
            <div class='d-flex flex-row justify-content-between'>
                <button type="button" class="btn btn-danger" id="saveButton">Save</button>
                <button type="button" class="btn text-white btn-outline-warning" id="deleteButton">Delete</button>
            </div>

        </div>
    </div>
</form>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100">
                    <fieldset class="form-group">
                        <label for="">Password</label>
                        <input class="form-control" type="password" value="" id="password" name="password">
                    </fieldset>
                </div>
                <div class="w-100">
                    <fieldset class="form-group">
                        <label for="">Password Confirm</label>
                        <input class="form-control" type="password" value="" id="password_confirm" name="password_confirm">
                    </fieldset>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="changePassword">Submit</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>


<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src='/js/animatedBtn.js'></script>
<script>
    const deleteUser = () => {
        let inputs = document.querySelectorAll('input');
        let json = {};
        inputs.forEach(el => {
            json[el.name] = el.value;
        })
        fetch(`${location.href}/delete`, {
                method: 'post',
                headers: {
                    'Content-type': 'application/jsonn'
                },
                body: JSON.stringify(json)
            })
            .then(response => response.json())
            .then(json => {
                alert(json.message);
                if (json.ok) {
                    history.back();
                }
            });
    }

    AnimatedBtn({
        domId: '#deleteButton',
        transitionTime: 2000,
        yourFunction: deleteUser,
        coverColor: 'rgba(255, 193, 7, 0.5)',
        baseColor: 'rgba(255, 193, 7, 1)',
    })

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

    document.querySelector('#changePassword').addEventListener('click', (e) => {
        let inputs = document.querySelectorAll('input');
        let json = {};

        inputs.forEach(el => {
            json[el.name] = el.value;
        })
        fetch(`${location.href}/password`, {
                method: 'post',
                headers: {
                    'Content-type': 'applicatio/jsonn'
                },
                body: JSON.stringify(json)
            })
            .then(response => response.json())
            .then(json => {
                alert(json.message);
                location.reload();
            });
    });
</script>


@endsection
