<option {{ !$selected ?: 'selected' }} data-content="<div class='d-flex align-items-center text-left'>
    <div class='taskEmployeeImg border-0 d-inline-block mr-1'>
    <img class='rounded-circle' src='{{ url('assets/images/avatar.png') }}'>  
    </div><div class='f-10 font-weight-light my-1'>{{$user->user->lastname }}</div></div>" value="{{ $userID ?? $user->id }}">
        {{ $user->user->lastname }} {{ $user->user->firstname }}
    </option> 