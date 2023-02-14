<?php

use App\Models\User;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('productstatus');
})->middleware('auth');

Route::get('login', function(){        
    return view ('login');
})->middleware('guest')->name('login');

Route::get('logout',function(Request $request){   
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('login');
})->middleware('auth')->name('logout');

Route::get('authenticate',function(Request $request){
    
    $userName = $request->query('username');
    $password = $request->query('password');    
    $users = DB::select("select id from users where username = '{$userName}' and password = '{$password}'");    
    
    if (!empty($users)) {        
        $user = User::find($users[0]->id); 
        Auth::login($user);
        return redirect()->route('productstatus');
    } else {
        return redirect()->route('login')->with('message','Invalid credentials');
    }

})->name('authenticate');

Route::get('productstatus',function(){    
    
    $suppliers = DB::select('select id,name from supplier order by 2');
    $grnnos = DB::select('select supplierid,lrentryno,id as grnid from lrentry where lrentrydate > \'2022-08-21\'');            
    return view('product_status',compact('suppliers','grnnos'));

})->middleware('auth')->name('productstatus') ;

Route::get('productstatusreport',function(Request $request){

    $fromDate = $request->query('fromDate');
    $toDate = $request->query('toDate');
    $supplierId = $request->query('supplierId');
    $grnNo = $request->query('grnNo');
    $reportId = $request->query('reportId');
   
    $queryForInvoiceIds = "select li.id 
                        from lrinvoice li 
                        inner join lrentry le on le.id = li.lrid and li.isactive = true and le.isactive =true 
                        and date(li.entrydate) between '{$fromDate}' and '{$toDate}'";
    if($supplierId > 0){
        $queryForInvoiceIds .= " and li.suppliercompanyid = {$supplierId}";
    }
    if($grnNo > 0){
        $queryForInvoiceIds .= " and le.lrentryno = '{$grnNo}'";
    }

    $invoiceIds = DB::select($queryForInvoiceIds);
    $invoiceIdsString = '';
    $invoiceIdsStringFinal = '';
    foreach ($invoiceIds as $invoiceId) {
        $invoiceIdsString .= $invoiceId->id .',';
    }
    $invoiceIdsStringFinal = substr($invoiceIdsString,0,strlen($invoiceIdsString)-1);

    if ($reportId == 1){

        $results = DB::select("select a.supplier,a.product,sum(coalesce(a.purchase,0)) as purchase,sum(coalesce(b.l2sales,0)) l2sales,sum(coalesce(b.l2stock,0)) l2stock,
        sum(coalesce(c.l4sales,0)) l4sales,sum(coalesce(c.l4stock,0)) l4stock,sum(coalesce(a.partyreturn,0)) partyreturn,
        sum(coalesce(a.whstock,0)) whstock
        from
        (select s.name as supplier ,i.productname as product,sum(coalesce(st.qty,0)) as purchase,st.id,sum(coalesce(st.purchasereturnqty,0)) partyreturn,
        sum(coalesce(st.qty,0)-coalesce(st.transferedout,0) + coalesce(st.transferedin,0)- coalesce(st.purchasereturnqty,0) - coalesce(st.journalqty,0)) whstock 
        from lrinvoiceitems lii
        inner join lrinvoice li on li.id = lii.lrinvoiceid and li.isactive = true and lii.isactive = true 
        and li.id in ({$invoiceIdsStringFinal})
        inner join items i on i.id = lii.itemid and i.isactive = true
        inner join supplier s on  s.id = li.suppliercompanyid
        inner join stock st on st.invoiceitemid = lii.id
        group by s.name,i.productname,st.id) a
        left join
        (select sg.stockid,sum(coalesce(sg.oqty,0)) as l2sales,sum(coalesce(sg.iqty,0)-coalesce(sg.oqty,0)-coalesce(sg.jqty,0)) as l2stock 
        from salablegoods sg where sg.locationid = 1 and sg.isactive = true group by sg.stockid) b on a.id = b.stockid
        left join 
        (select sg.stockid,sum(coalesce(sg.oqty,0)) as l4sales,sum(coalesce(sg.iqty,0)-coalesce(sg.oqty,0)-coalesce(sg.jqty,0)) as l4stock 
        from salablegoods sg where sg.locationid = 54 and sg.isactive = true group by sg.stockid) c on a.id = c.stockid
        group by a.supplier,a.product");

    } else {

        $results = DB::select("Select a.supplier,a.product,sum(coalesce(a.purchase,0)) purchase,
        sum(coalesce(a.l2sales, 0)) l2sales,sum(coalesce(a.l2stock,0)) l2stock,
        sum(coalesce(a.l4sales, 0)) l4sales,sum(coalesce(a.l4stock,0)) l4stock,
        sum(coalesce(a.partyreturn, 0)) partyreturn,sum(coalesce(a.whstock,0)) whstock
        from
        (select s.id,s.name as supplier ,i.productname as product,sum(coalesce(st.qty,0)) as purchase,0 l2sales,0 l2stock,0 l4sales,0 l4stock,0 partyreturn,0 whstock
        From lrinvoiceitems lii
        inner Join lrinvoice li on li.id = lii.lrinvoiceid And li.isactive = true And lii.isactive = true And li.id in ({$invoiceIdsStringFinal})
        inner Join items i on i.id = lii.itemid And i.isactive = true
        inner Join supplier s on  s.id = li.suppliercompanyid
        inner Join stock st on st.invoiceitemid = lii.id
        group by s.name, i.productname, s.id
        union all
        select st.supplierid,s.name,i.productname,0,sum(coalesce(bi.qty,0)) l2sales,0,0,0,0,0
        from billitems bi
        inner join stock st on st.id = bi.stockid and bi.locationid = 1 
        and date(bi.createdon) between '{$fromDate}' and  '{$toDate}' and st.isactive = true and bi.isactive = true  
        inner join supplier s on s.id =  st.supplierid and s.id = {$supplierId}
        inner join items i on i.id = st.itemid and i.isactive  = true
        group by st.supplierid,s.name,i.productname
        union all
        Select x.supplierid,x.name,x.productname,0,0,sum(x.l2Stock) l2stock,0,0,0,0 from
        (select sg.id,s.name,sg.locationid,sg.supplierid,i.productname,sum(coalesce(sg.iqty,0)-coalesce(sg.oqty,0)-coalesce(sg.jqty,0)) as l2stock 
        From salablegoods sg 
        inner Join supplier s on s.id = sg.supplierid
        inner Join items i on i.id = sg.itemid And sg.locationid = 1 And sg.isactive = true And i.isactive = true And sg.supplierid = {$supplierId}
        group by sg.id, sg.locationid, sg.supplierid, i.productname, s.name) x
        group by x.supplierid, x.productname, x.name
        union all
        select st.supplierid,s.name,i.productname,0,0,0,sum(coalesce(bi.qty,0)) l4sales,0,0,0
        from billitems bi
        inner join stock st on st.id = bi.stockid and bi.locationid = 54 
        and date(bi.createdon) between '{$fromDate}' and  '{$toDate}' and st.isactive = true and bi.isactive = true  
        inner join supplier s on s.id =  st.supplierid and s.id = {$supplierId}
        inner join items i on i.id = st.itemid and i.isactive  = true
        group by st.supplierid,s.name,i.productname
        union all
        Select x.supplierid,x.name,x.productname,0,0,0,0,sum(x.l4Stock) l4stock,0,0 from
        (select sg.id,s.name,sg.locationid,sg.supplierid,i.productname,sum(coalesce(sg.iqty,0)-coalesce(sg.oqty,0)-coalesce(sg.jqty,0)) as l4stock 
        From salablegoods sg 
        inner Join supplier s on s.id = sg.supplierid
        inner Join items i on i.id = sg.itemid And sg.locationid = 54 And sg.isactive = true And i.isactive = true And sg.supplierid = {$supplierId}
        group by sg.id, sg.locationid, sg.supplierid, i.productname, s.name) x
        group by x.supplierid, x.productname, x.name
        union all
        Select st.supplierid,s.name,i.productname,0,0,0,0,0,sum(coalesce(st.purchasereturnqty,0)) partyreturn,0 
        From stock st 
        inner Join supplier s on s.id = st.supplierid And s.id = {$supplierId}
        inner Join items i on i.id = st.itemid And st.isactive = true And i.isactive = true 
        group by st.supplierid, i.productname, s.name
        union all
        Select st.supplierid,s.name,i.productname,0,0,0,0,0,0,sum(st.qty-st.transfered-st.purchasereturnqty-st.journalqty) whstock
        From stock st
        inner Join supplier s on s.id = st.supplierid And st.isactive = true And s.id = {$supplierId}
        inner Join items i on i.id = st.itemid And i.isactive = true
        group by st.supplierid, s.name, i.productname) a
        group by a.supplier, a.product");

    }
    
    return view('product_status_report',compact('results'));

})->middleware('auth')->name('productstatusreport');

