@extends('layout')

@section('content')

<div class="wide-block pb-1 pt-4">

    <form method="GET" action="{{route('productstatusreport')}}" id="myForm">
        @csrf
        <div class="form-group">
            <div class="input-wrapper">
                <label class="label pb-1">From</label>
                <input type="date" class="form-control" id="fromDate" name="fromDate" value="2022-08-22" min="2022-08-22" required>               
            </div>
        </div>

        <div class="form-group">
            <div class="input-wrapper">
                <label class="label pb-1">To</label>
                <input type="date" class="form-control" id="toDate" name="toDate" required> 
                <!-- value="@php date('Y').'-'.date('m').'-'.date('d') @endphp"                 -->
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="label">Supplier</label>
                <select id="supplierId" name="supplierId" autocomplete="off" required>
                    <option value="0">Select a supplier</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}">{{ $supplier->name }}</option> 
                    @endforeach                   
                </select>
            </div>
        </div>
       
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="label">Grn No</label>
                <select class="form-control custom-select" id="grnno" name="grnNo" autocomplete="off"> 
                <option value="0">Select a grnno</option>                                                          
                </select>
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="label">Report Option</label>
                <select class="form-control custom-select" id="reportId" name="reportId" required>                    
                    <option value="1">Normal</option>
                    <option value="2">Overall</option>                    
                </select>
            </div>
        </div>
        
        <div class="input-wrapper pt-2">
            <button type="button" class="btn btn-info btn-lg btn-block" onclick="validate()">Search</button>
        </div>
        
    </form>

</div>

<script>

    // new TomSelect("#supplierId",{
    //     create: false,
    // });

    // new TomSelect("#grnno",{
    //     create: false,
    // });
               
    const dateInput = document.getElementById('toDate');    
    dateInput.value = formatDate();

    function padTo2Digits(num) {
    return num.toString().padStart(2, '0');
    }

    function formatDate(date = new Date()) {
    return [
        date.getFullYear(),
        padTo2Digits(date.getMonth() + 1),
        padTo2Digits(date.getDate()),
    ].join('-');
    }

    document.addEventListener("DOMContentLoaded", function(event) {

        new TomSelect("#supplierId",{});
                
    });
   
    document.querySelector("#supplierId").addEventListener('change',function(){
        var grnnos = @json($grnnos);                        
        var grnDropdown = document.querySelector("#grnno");
        var supplierId =  parseInt(this.value);
        var grnData = grnnos.filter((grnno) => grnno.supplierid == supplierId); 
        grnData.sort(function(a,b){
            return b.grnid - a.grnid;
        });  
        grnDropdown.innerHTML = "";             
        var option = document.createElement("option");
        option.value = 0;
        option.textContent = "Select a grn no";                     
        grnDropdown.appendChild(option);
        for(var data in grnData){
            var opt = document.createElement("option");                       
            opt.value = grnData[data].lrentryno;
            opt.textContent = grnData[data].lrentryno; 
            grnDropdown.appendChild(opt);            
        }                
    });

    function validate() {
        var supplierDropdown = document.querySelector('#supplierId')
        var grnDropdown = document.querySelector('#grnno');
        if(supplierDropdown.value == 0){
            alert('Please select a supplier!');
            return;
        } else if (grnDropdown.options.length == 1) {
            alert('Invoices not available!');
            return;
        }       
        document.getElementById("myForm").submit();
    }

</script>

@endsection