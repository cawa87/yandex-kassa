@extends(config('laravel_balance.layout'))

@section('content')
    <div class="container">
        <div class="main-container">
            <div class="row">
                <div class="col-md-12 page-content">
                  <ul>
                      @foreach($transactions as $transaction)
                      <li>
                          {{$transaction->hash}}/{{$transaction->value}}
                      </li>
                      @endforeach
                  </ul>

                </div>
            </div>
            <!--/.page-content-->
        </div>
    </div>
    </div>
@endsection