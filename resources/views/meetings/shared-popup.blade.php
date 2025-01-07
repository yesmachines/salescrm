@if(!empty($meeting->business_card))
<div class="mt-2">
    <img class="card-img-top pop" src="{{asset('storage') . '/' . $meeting->business_card}}" alt="Business Card">
</div>
@endif

<div class="mt-2 text-blue">{{$meeting->title}}</div>

<div class="card team-card card-border mt-2">
    <div class="card-body p-1">
        <div class="media">
            <div class="media-body">
                <span class="text-green">Shared By</span>
                <div class="text-truncate">{{$meeting->sharedBy->name}}</div>
                <span class="text-purple">Shared To</span>
                <div class="text-truncate">{{$meeting->sharedTo->name}}</div>
            </div>
        </div>
    </div>
</div>

<div class="collapse-simple mt-2">
    <div class="card">
        <div class="card-header">
            <a role="button" data-bs-toggle="collapse" href="#biography" aria-expanded="true">Meeting Notes </a>
        </div>
        <div id="biography" class="collapse show">
            <div class="card-body">
                <p>{{$meeting->scheduled_notes}}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <a role="button" data-bs-toggle="collapse" href="#products" aria-expanded="true">Products </a>
        </div>
        <div id="products" class="collapse show">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($meeting->products as $product)
                    <li class="list-group-item">
                        {{$product->title}}
                        <p class="text-blue">{{$product->brand}}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>