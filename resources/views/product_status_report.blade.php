@extends('layout')

@section('content')

<div class="wide-block pt-3">
    <div class="card text-white bg-info mb-2">
        <div class="card-header">{{$results[0]->supplier}}</div>
    </div>
</div>

<div class="wide-block pt-2">
    <div class="card text-white bg-info mb-2">
        <div class="card-header">SUMMARY</div>
            <div class="card-body">
                <div class="row mb-2">
                    <label class="col-6 form-label ">{{ __('Purchase') }}</label>
                    <label class="from-label col-1">{{ __(':') }}</label>
                    <strong class="col-5">
                        @php                            
                            $purchaseTotal = array_sum(array_column($results,'purchase'));
                        @endphp
                        {{
                            $purchaseTotal != 0 ?
                            number_format($purchaseTotal,2) :
                            number_format(0,2)    
                        }}
                    </strong>
                 </div> 
                 <div class="row mb-2">
                    <label class="col-6 form-label ">{{ __('Sales') }}</label>
                    <label class="from-label col-1">{{ __(':') }}</label>
                    <strong class="col-5">
                        @php                            
                            $salesTotal = array_sum(array_column($results,'l2sales')) + array_sum(array_column($results,'l4sales')) ;
                        @endphp
                        {{
                            $salesTotal != 0 ?
                            number_format($salesTotal,2) :
                            number_format(0,2)    
                        }}
                    </strong>
                 </div>  
                 <div class="row mb-2">
                    <label class="col-6 form-label ">{{ __('Stock') }}</label>
                    <label class="from-label col-1">{{ __(':') }}</label>
                    <strong class="col-5">
                        @php                            
                            $stockTotal = array_sum(array_column($results,'l2stock')) + array_sum(array_column($results,'l4stock')) + array_sum(array_column($results,'whstock')) ;
                        @endphp
                        {{
                            $stockTotal != 0 ?
                            number_format($stockTotal,2) :
                            number_format(0,2)    
                        }}
                    </strong>
                 </div>  
                 <div class="row">
                    <label class="col-6 form-label ">{{ __('Return') }}</label>
                    <label class="from-label col-1">{{ __(':') }}</label>
                    <strong class="col-5">
                        @php                            
                            $returnTotal = array_sum(array_column($results,'partyreturn'));
                        @endphp
                        {{
                            $returnTotal != 0 ?
                            number_format($returnTotal,2) :
                            number_format(0,2)    
                        }}
                    </strong>
                 </div>                
            </div>
        </div>
    </div>
</div>

@foreach ($results as $result)            
    <div class="wide-block pt-2">
        <div class="card text-white bg-info mb-2">
            <div class="card-header">{{$result->product}}</div>
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-6 form-label ">{{ __('Purchase') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->purchase != 0 ?
                                number_format($result->purchase,2) :
                                number_format(0,2)    
                            }}
                        </strong>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6 form-label ">{{ __('L2 Sales') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->l2sales != 0 ?
                                number_format($result->l2sales,2) :
                                number_format(0,2)
                            }}
                        </strong>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6 form-label ">{{ __('L2 Stock') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->l2stock != 0 ?
                                number_format($result->l2stock,2) :
                                number_format(0,2)
                            }}
                        </strong>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6 form-label ">{{ __('L4 Sales') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->l4sales != 0 ?
                                number_format($result->l4sales,2) :
                                number_format(0,2)
                            }}
                        </strong>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6 form-label ">{{ __('L4 Stock') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->l4stock != 0 ?
                                number_format($result->l4stock,2) :
                                number_format(0,2)
                            }}
                        </strong>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6 form-label ">{{ __('Party Return') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->partyreturn != 0 ?
                                number_format($result->partyreturn,2) :
                                number_format(0,2)
                            }}
                        </strong>
                    </div>
                    <div class="row">
                        <label class="col-6 form-label ">{{ __('WH Stock') }}</label>
                        <label class="from-label col-1">{{ __(':') }}</label>
                        <strong class="col-5">
                            {{
                                $result->whstock != 0 ?
                                number_format($result->whstock,2) :
                                number_format(0,2)
                            }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
    
@endsection

<!-- <div class="wide-block pt-3">

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Supplier</th>
                    <th scope="col">Product</th>
                    <th scope="col">Purchase</th>
                    <th scope="col">L2 Sales</th>
                    <th scope="col">L2 Stock</th>
                    <th scope="col">L4 Sales</th>
                    <th scope="col">L4 Stock</th>
                    <th scope="col">Return</th>
                    <th scope="col">WH Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>                    
                        <td>{{$result->supplier}}</td>
                        <td>{{$result->product}}</td>
                        <td>{{$result->purchase}}</td>
                        <td>{{$result->l2sales}}</td>
                        <td>{{$result->l2stock}}</td>
                        <td>{{$result->l4sales}}</td>
                        <td>{{$result->l4stock}}</td>
                        <td>{{$result->partyreturn}}</td>
                        <td>{{$result->whstock}}</td>                        
                    </tr>               
                @endforeach
            </tbody>
        </table>
    </div>

</div> -->