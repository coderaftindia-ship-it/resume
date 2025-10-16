<!-- Footer -->
<footer class="footer">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col-md-12">
            <div class="copyright text-center  text-lg-left  text-muted">
                {{__('messages.all_rights_reserved')}} &copy; {{ date('Y') }} <a
                        href="{{getAdminSettingValue('website')}}" class="font-weight-bold ml-1 footer-link-color"
                        target="_blank">{{ getAdminSettingValue('company_name') }}</a>
            </div>
        </div>
    </div>
</footer>
