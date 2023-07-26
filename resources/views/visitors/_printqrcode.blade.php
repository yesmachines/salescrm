<div class="modal-body">
    <ul class="list-group">
        <li class="list-group-item">
            <b>Name :</b>
            {{$visitor->customer->fullname}}
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item">
            <b>Company :</b>
            {{$visitor->company->company}}
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item">
            <b>Email :</b>
            {{$visitor->customer->email}}
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item">
            <b>Phone :</b>
            {{$visitor->customer->phone}}
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item">
            <b>CodeNo :</b>
            {{$visitor->codeno}}
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item">
            <b>Checkin :</b>
            {{date("d-m-Y",strtotime($visitor->checkin))}}
        </li>
    </ul>
</div>