<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taqua Silks</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">    
</head>
<body>
    
    <div class="appHeader bg-info text-light">  
        @if(Route::currentRouteName() != 'productstatus' && Route::currentRouteName() != 'login')            
            <div class="left">
                <a href="javascript:;" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
                </a>
            </div>           
        @endif
        <div class="pageTitle">TAQUA SILKS</div> 
        @if (Route::currentRouteName() == 'productstatus')
            <div class="right">
                <a class="btn btn-info" type="button" data-toggle="modal" data-target="#DialogBasic" onclick="updateModal()">
                    <ion-icon name="log-out-outline" role="img" class="md hydrated" aria-label="log out outline"></ion-icon>
                </a>
            </div>
        @endif      
    </div>

    <main class="mt-5">
        @yield('content')
    </main>

    <div class="row mt-5">
        <div class="appBottomMenu bg-info text-light">
            <a href="#" class="item">
                <div class="col">
                    <h5 style="color: white" id="fYear"></h5>                    
                </div>
            </a>
        </div>
    </div>

    <div class="modal fade dialogbox" id="DialogBasic" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign Out</h5>
                </div>
                <div class="modal-body">
                    Are you sure ?
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a class="btn btn-text-secondary" data-dismiss="modal" onclick="updateModal2()">CLOSE</a>                       
                        <a type="button" class="btn btn-text-primary" data-dismiss="modal" onclick="redirectToLogin()">OK</a>                                                                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/lib/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/lib/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/base.js')}}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>    
    <script src="{{asset('assets/js/plugins/circle-progress.min.js')}}"></script>
    <script>

        function updateModal(){
            var modal = document.getElementById('DialogBasic');
            modal.setAttribute("class","modal fade dialogbox show");
            modal.setAttribute("style","display: block");
            modal.setAttribute("aria-modal","true");
        }

        function updateModal2(){
            var modal = document.getElementById('DialogBasic');
            modal.setAttribute("class","modal fade dialogbox");
            modal.setAttribute("style","display: none");
            modal.setAttribute("aria-modal","false");
        }

        function redirectToLogin(){
            window.location.href = "{{ route('logout') }}";
        }

        

        window.onload = function getCurrentFiscalYear() {
        //get current date
        var today = new Date();
        
        //get current month
        var curMonth = today.getMonth();
        
        var fiscalYr = "";
        if (curMonth > 3) { //
            var nextYr1 = (today.getFullYear() + 1).toString();
            fiscalYr = today.getFullYear().toString() + "-" + nextYr1.charAt(2) + nextYr1.charAt(3);
        } else {
            var nextYr2 = today.getFullYear().toString();
            fiscalYr = (today.getFullYear() - 1).toString() + "-" + nextYr2.charAt(2) + nextYr2.charAt(3);
        }
        console.log(fiscalYr);
        document.getElementById('fYear').innerText = "TAQUA SILKS " + fiscalYr;

        };

    </script>

</body>
</html>