@php
$content = "<div class='d-flex align-items-center text-left'>
    <div class='taskEmployeeImg border-0 d-inline-block mr-1'>
        <img class='rounded-circle' src='".URL::to("/assets/images/". $user->avatar)."'>
    </div>
    <div>". htmlentities($user->userBadge());

        if (isset($additionalText) && !is_null($additionalText)) {
        $content .= "<div class='f-10 font-weight-light my-1'>".$additionalText."</div>";
        }

        $content.="</div>";

    if($agent){
    $content .= ' ['.$user->user->email.'] ';
    }

    if($pill){
    $content = "<span class='badge badge-pill badge-light border'>".$content."</span>";
    }

    @endphp

    <option {{ !$selected ?: 'selected' }} data-content="{!! $content !!}" value="{{ $userID ?? $user->id }}">
        {{ $user->user->lastname }} {{ $user->user->firstname }}
    </option>
