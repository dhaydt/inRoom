<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{\App\CPU\translate('Ready to Leave')}}?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{\App\CPU\translate('Select "Logout" below if you are ready to end your current session')}}.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{\App\CPU\translate('Cancel')}}</button>
                <a class="btn btn-primary" href="{{route('seller.auth.logout')}}">{{\App\CPU\translate('Logout')}}</a>
            </div>
        </div>
    </div>
</div>
